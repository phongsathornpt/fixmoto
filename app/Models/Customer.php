<?php

require_once core('Model.php');

/**
 * Customer Model
 * Manages customer data with ORM-style queries
 */
class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'id';
    protected $fillable = ['f_name', 'l_name', 'mobile_num'];

    /**
     * Find customer by mobile number
     */
    public function findByMobile(string $mobile): ?array
    {
        return $this->select(['id', 'f_name', 'l_name', 'mobile_num'])
            ->where('mobile_num', $mobile)
            ->first();
    }

    /**
     * Get all customers matching mobile (for backwards compatibility)
     */
    public function searchByMobile(string $mobile): array
    {
        return $this->select(['id', 'f_name', 'l_name', 'mobile_num'])
            ->where('mobile_num', $mobile)
            ->get();
    }

    /**
     * Create a new customer
     */
    public function createCustomer(string $fname, string $lname, string $mobile): int
    {
        return $this->insert([
            'f_name' => $fname,
            'l_name' => $lname,
            'mobile_num' => $mobile
        ]);
    }

    /**
     * Get the last customer ID
     */
    public function getLastId(): ?int
    {
        $result = $this->rawFirst("SELECT MAX(id) as max_id FROM {$this->table}");
        return $result['max_id'] ?? null;
    }

    /**
     * Search customers by name
     */
    public function searchByName(string $name): array
    {
        return $this->rawSelect(
            "SELECT id, f_name, l_name, mobile_num 
             FROM {$this->table} 
             WHERE f_name LIKE :name OR l_name LIKE :name",
            ['name' => "%{$name}%"]
        );
    }
}
