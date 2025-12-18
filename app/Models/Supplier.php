<?php

require_once core('Model.php');

/**
 * Supplier Model
 * Manages suppliers with ORM-style queries
 */
class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];

    /**
     * Get all suppliers
     */
    public function all(): array
    {
        return $this->select(['id', 'name'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Create a new supplier
     */
    public function createSupplier(string $name): int
    {
        return $this->insert(['name' => $name]);
    }

    /**
     * Find supplier by name
     */
    public function findByName(string $name): ?array
    {
        return $this->where('name', $name)->first();
    }

    /**
     * Search suppliers by name
     */
    public function search(string $query): array
    {
        return $this->rawSelect(
            "SELECT id, name FROM {$this->table} 
             WHERE name LIKE :query 
             ORDER BY name",
            ['query' => "%{$query}%"]
        );
    }

    /**
     * Get supplier with part count
     */
    public function withPartCount(): array
    {
        return $this->rawSelect(
            "SELECT s.id, s.name, COUNT(i.id) as part_count
             FROM {$this->table} s
             LEFT JOIN inventory i ON s.id = i.supplier_id
             GROUP BY s.id, s.name
             ORDER BY s.name"
        );
    }
}
