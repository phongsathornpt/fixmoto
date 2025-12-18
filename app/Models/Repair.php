<?php

require_once core('Model.php');

/**
 * Fix Model
 * Manages repair/fix records with ORM-style queries
 */
class Repair extends Model
{
    protected $table = 'repairs';
    protected $primaryKey = 'id';
    protected $fillable = ['customer_id', 'date', 'brand', 'detail', 'status_id', 'plate'];

    /**
     * Get all repairs with basic info
     */
    public function all(): array
    {
        return $this->select(['id', 'customer_id', 'date', 'brand', 'detail', 'status_id', 'plate'])
            ->orderBy('date', 'DESC')
            ->get();
    }

    /**
     * Find repair by ID
     */
    public function find($id): ?array
    {
        return $this->select(['id', 'customer_id', 'date', 'brand', 'detail', 'status_id', 'plate'])
            ->where('id', $id)
            ->first();
    }

    /**
     * Create a new repair record
     */
    public function createRepair(int $customerId, string $plate, string $brand, string $detail): int
    {
        return $this->insert([
            'customer_id' => $customerId,
            'date' => date("Y-m-d H:i:s"),
            'brand' => $brand,
            'detail' => $detail,
            'status_id' => 1,
            'plate' => $plate
        ]);
    }

    /**
     * Get customer name for a repair
     */
    public function getCustomerName(int $repairId): ?array
    {
        $result = $this->rawFirst(
            "SELECT c.f_name, c.l_name 
             FROM {$this->table} r
             INNER JOIN customer c ON r.customer_id = c.id 
             WHERE r.id = :id",
            ['id' => $repairId]
        );
        return $result;
    }

    /**
     * Get repair status
     */
    public function getStatus(int $repairId): string
    {
        $result = $this->rawFirst(
            "SELECT rs.name 
             FROM {$this->table} r
             INNER JOIN repair_statuses rs ON r.status_id = rs.id 
             WHERE r.id = :id",
            ['id' => $repairId]
        );
        return $result['name'] ?? '';
    }

    /**
     * Update repair status
     */
    public function updateStatus(int $repairId, int $statusId): int
    {
        return $this->where('id', $repairId)
            ->update(['status_id' => $statusId]);
    }

    /**
     * Get used parts for a repair
     */
    public function getUsedParts(int $repairId): array
    {
        return $this->rawSelect(
            "SELECT i.name, ri.serial_number 
             FROM repair_items ri
             INNER JOIN inventory_serials s ON ri.serial_number = s.serial_number 
             INNER JOIN inventory i ON s.inventory_id = i.id 
             WHERE ri.repair_id = :id",
            ['id' => $repairId]
        );
    }

    /**
     * Add used part to repair
     */
    public function addUsedPart(string $serialNumber, int $repairId): bool
    {
        $this->rawQuery(
            "INSERT INTO repair_items (repair_id, serial_number) VALUES (:repair_id, :serial_number)",
            ['repair_id' => $repairId, 'serial_number' => $serialNumber]
        );
        return true;
    }

    /**
     * Get repairs by status
     */
    public function getByStatus(int $statusId): array
    {
        return $this->where('status_id', $statusId)
            ->orderBy('date', 'DESC')
            ->get();
    }

    /**
     * Get repairs by customer
     */
    public function getByCustomer(int $customerId): array
    {
        return $this->where('customer_id', $customerId)
            ->orderBy('date', 'DESC')
            ->get();
    }

    /**
     * Get recent repairs with pagination
     */
    public function getRecent(int $limit = 10, int $offset = 0): array
    {
        return $this->orderBy('date', 'DESC')
            ->take($limit, $offset)
            ->get();
    }
}
