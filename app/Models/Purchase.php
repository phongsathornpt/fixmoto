<?php

require_once core('Model.php');

/**
 * Purchase Model
 * Manages purchase orders with ORM-style queries
 */
class Purchase extends Model
{
    protected $table = 'purchases';
    protected $primaryKey = 'id';
    protected $fillable = ['supplier_id', 'buy_date', 'status_id', 'recv_date', 'due_pay_date', 'pay_date'];

    /**
     * Get all purchases with supplier info
     */
    public function all(): array
    {
        return $this->rawSelect(
            "SELECT p.id, s.name as supplier_name, p.buy_date 
             FROM {$this->table} p
             INNER JOIN suppliers s ON p.supplier_id = s.id
             ORDER BY p.buy_date DESC"
        );
    }

    /**
     * Get active purchases (status = 2)
     */
    public function allActive(): array
    {
        return $this->rawSelect(
            "SELECT p.id, s.name as supplier_name, p.buy_date 
             FROM {$this->table} p
             INNER JOIN suppliers s ON p.supplier_id = s.id
             WHERE p.status_id = 2
             ORDER BY p.buy_date DESC"
        );
    }

    /**
     * Find purchase by ID
     */
    public function find($id): ?array
    {
        return $this->where('id', $id)->first();
    }

    /**
     * Create a new purchase order
     */
    public function createPurchase(int $supplierId, string $buyDate, string $duePayDate): int
    {
        return $this->insert([
            'supplier_id' => $supplierId,
            'buy_date' => $buyDate,
            'status_id' => 1,
            'due_pay_date' => $duePayDate
        ]);
    }

    /**
     * Add item to purchase order
     */
    public function addDetail(int $purchaseId, int $inventoryId, int $amount): bool
    {
        $this->rawQuery(
            "INSERT INTO purchase_items (inventory_id, purchase_id, amount) 
             VALUES (:inventory_id, :purchase_id, :amount)",
            [
                'inventory_id' => $inventoryId,
                'purchase_id' => $purchaseId,
                'amount' => $amount
            ]
        );
        return true;
    }

    /**
     * Get purchase order details
     */
    public function getDetails(int $purchaseId): array
    {
        return $this->rawSelect(
            "SELECT pi.inventory_id, i.name, pi.amount, 
                    (pi.amount * i.cost) as total_cost 
             FROM purchase_items pi
             INNER JOIN inventory i ON i.id = pi.inventory_id 
             WHERE pi.purchase_id = :id",
            ['id' => $purchaseId]
        );
    }

    /**
     * Calculate total cost of purchase order
     */
    public function getTotalCost(int $purchaseId): float
    {
        $result = $this->rawFirst(
            "SELECT SUM(pi.amount * i.cost) as total
             FROM purchase_items pi
             INNER JOIN inventory i ON pi.inventory_id = i.id 
             WHERE pi.purchase_id = :id",
            ['id' => $purchaseId]
        );
        return (float) ($result['total'] ?? 0);
    }

    /**
     * Get purchase status
     */
    public function getStatus(int $purchaseId): string
    {
        $result = $this->rawFirst(
            "SELECT bs.name 
             FROM buy_statuses bs
             INNER JOIN {$this->table} p ON p.status_id = bs.id 
             WHERE p.id = :id",
            ['id' => $purchaseId]
        );
        return $result['name'] ?? '';
    }

    /**
     * Get supplier name for purchase
     */
    public function getSupplierName(int $purchaseId): string
    {
        $result = $this->rawFirst(
            "SELECT s.name 
             FROM {$this->table} p
             INNER JOIN suppliers s ON p.supplier_id = s.id 
             WHERE p.id = :id",
            ['id' => $purchaseId]
        );
        return $result['name'] ?? '';
    }

    /**
     * Get purchase date
     */
    public function getBuyDate(int $purchaseId): string
    {
        $result = $this->where('id', $purchaseId)->select(['buy_date'])->first();
        return $result['buy_date'] ?? '';
    }

    /**
     * Activate purchase order
     */
    public function activate(int $purchaseId): int
    {
        return $this->where('id', $purchaseId)
            ->update(['status_id' => 2]);
    }

    /**
     * Mark purchase as paid
     */
    public function markPaid(int $purchaseId): int
    {
        return $this->where('id', $purchaseId)
            ->update(['pay_date' => date("Y-m-d")]);
    }

    /**
     * Mark purchase as received
     */
    public function markReceived(int $purchaseId): int
    {
        return $this->where('id', $purchaseId)
            ->update(['recv_date' => date("Y-m-d")]);
    }

    /**
     * Check if purchase is paid
     */
    public function isPaid(int $purchaseId): bool
    {
        $result = $this->where('id', $purchaseId)
            ->select(['pay_date'])
            ->first();
        return !empty($result['pay_date']);
    }

    /**
     * Check if purchase is received
     */
    public function isReceived(int $purchaseId): bool
    {
        $result = $this->where('id', $purchaseId)
            ->select(['recv_date'])
            ->first();
        return !empty($result['recv_date']);
    }

    /**
     * Get last purchase ID
     */
    public function getLastId(): ?int
    {
        $result = $this->rawFirst("SELECT MAX(id) as max_id FROM {$this->table}");
        return $result['max_id'] ?? null;
    }

    /**
     * Get pending purchases (not paid)
     */
    public function getPending(): array
    {
        return $this->rawSelect(
            "SELECT p.id, s.name as supplier_name, p.buy_date, p.due_pay_date
             FROM {$this->table} p
             INNER JOIN suppliers s ON p.supplier_id = s.id
             WHERE p.pay_date IS NULL
             ORDER BY p.due_pay_date ASC"
        );
    }

    /**
     * Get purchases by status
     */
    public function getByStatus(int $statusId): array
    {
        return $this->rawSelect(
            "SELECT p.id, s.name as supplier_name, p.buy_date 
             FROM {$this->table} p
             INNER JOIN suppliers s ON p.supplier_id = s.id
             WHERE p.status_id = :status_id
             ORDER BY p.buy_date DESC",
            ['status_id' => $statusId]
        );
    }
}
