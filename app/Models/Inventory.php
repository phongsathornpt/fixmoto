<?php

require_once core('Model.php');

/**
 * Part Model
 * Manages parts inventory with ORM-style queries
 */
class Inventory extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'id';
    protected $fillable = ['supplier_id', 'name', 'cost', 'price'];

    /**
     * Get all parts with stock info
     */
    public function allWithStock(): array
    {
        return $this->rawSelect(
            "SELECT i.id, i.name, i.cost, i.price, s.quantity 
             FROM {$this->table} i
             INNER JOIN inventory_stock s ON i.id = s.inventory_id
             ORDER BY i.name"
        );
    }

    /**
     * Find parts by supplier
     */
    public function findBySupplier(int $supplierId): array
    {
        return $this->select(['id', 'name'])
            ->where('supplier_id', $supplierId)
            ->get();
    }

    /**
     * Create a new part with stock entry
     */
    public function createInventory(int $supplierId, string $name, float $cost, float $price): int
    {
        $db = $this->db;

        try {
            $db->beginTransaction();

            // Insert part
            $inventoryId = $this->insert([
                'supplier_id' => $supplierId,
                'name' => $name,
                'cost' => $cost,
                'price' => $price
            ]);

            // Create stock entry
            $this->rawQuery(
                "INSERT INTO inventory_stock (inventory_id, quantity) VALUES (:inventory_id, 0)",
                ['inventory_id' => $inventoryId]
            );

            $db->commit();
            return $inventoryId;

        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Get current stock for a part
     */
    public function getStock(int $inventoryId): int
    {
        $result = $this->rawFirst(
            "SELECT quantity FROM inventory_stock WHERE inventory_id = :id",
            ['id' => $inventoryId]
        );
        return (int) ($result['quantity'] ?? 0);
    }

    /**
     * Update stock quantity
     */
    public function updateStock(int $inventoryId, int $quantity): bool
    {
        $this->rawQuery(
            "UPDATE inventory_stock SET quantity = :quantity WHERE inventory_id = :id",
            ['quantity' => $quantity, 'id' => $inventoryId]
        );
        return true;
    }

    /**
     * Add to stock (increment)
     */
    public function addStock(int $inventoryId, int $amount): bool
    {
        $currentStock = $this->getStock($inventoryId);
        return $this->updateStock($inventoryId, $currentStock + $amount);
    }

    /**
     * Reduce stock (decrement)
     */
    public function reduceStock(int $inventoryId, int $amount): bool
    {
        $currentStock = $this->getStock($inventoryId);
        $newStock = max(0, $currentStock - $amount);
        return $this->updateStock($inventoryId, $newStock);
    }

    /**
     * Check if serial number is available
     */
    public function checkSerialNumber(string $serialNumber): ?array
    {
        return $this->rawFirst(
            "SELECT serial_number FROM inventory_serials
             WHERE serial_number = :serial AND status != 'used'",
            ['serial' => $serialNumber]
        );
    }

    /**
     * Set serial number status
     */
    public function setSerialStatus(string $serialNumber, string $status = 'used'): bool
    {
        $this->rawQuery(
            "UPDATE inventory_serials SET status = :status WHERE serial_number = :serial",
            ['status' => $status, 'serial' => $serialNumber]
        );
        return true;
    }

    /**
     * Get inventory ID by serial number
     */
    public function getInventoryIdBySerial(string $serialNumber): ?int
    {
        $result = $this->rawFirst(
            "SELECT inventory_id FROM inventory_serials WHERE serial_number = :serial",
            ['serial' => $serialNumber]
        );
        return $result['inventory_id'] ?? null;
    }

    /**
     * Get low stock parts (below threshold)
     */
    public function getLowStock(int $threshold = 5): array
    {
        return $this->rawSelect(
            "SELECT i.id, i.name, s.quantity 
             FROM {$this->table} i
             INNER JOIN inventory_stock s ON i.id = s.inventory_id
             WHERE s.quantity <= :threshold
             ORDER BY s.quantity ASC",
            ['threshold' => $threshold]
        );
    }

    /**
     * Search parts by description
     */
    public function search(string $query): array
    {
        return $this->rawSelect(
            "SELECT i.id, i.name, i.cost, i.price, s.quantity 
             FROM {$this->table} i
             INNER JOIN inventory_stock s ON i.id = s.inventory_id
             WHERE i.name LIKE :query",
            ['query' => "%{$query}%"]
        );
    }
}
