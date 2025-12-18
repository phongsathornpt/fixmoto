<?php

/**
 * Modern ORM-like Model Base Class
 * Features: Fluent query builder, SQL injection prevention, type safety
 */
class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];

    // Query builder state
    private $query = '';
    private $bindings = [];
    private $selectColumns = '*';
    private $whereConditions = [];
    private $orderBy = [];
    private $limitValue = null;
    private $offsetValue = null;
    private $joins = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->resetQuery();
    }

    /**
     * Reset query builder state
     */
    protected function resetQuery(): self
    {
        $this->query = '';
        $this->bindings = [];
        $this->selectColumns = '*';
        $this->whereConditions = [];
        $this->orderBy = [];
        $this->limitValue = null;
        $this->offsetValue = null;
        $this->joins = [];
        return $this;
    }

    // ==================== FLUENT QUERY BUILDER ====================

    /**
     * Select specific columns
     * @param string|array $columns
     */
    public function select($columns = '*'): self
    {
        if (is_array($columns)) {
            $this->selectColumns = implode(', ', array_map([$this, 'escapeIdentifier'], $columns));
        } else {
            $this->selectColumns = $columns;
        }
        return $this;
    }

    /**
     * Add WHERE condition (AND)
     */
    public function where(string $column, $operator, $value = null): self
    {
        // Support shorthand: where('column', 'value') means where('column', '=', 'value')
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $placeholder = ':where_' . count($this->bindings);
        $this->whereConditions[] = [
            'type' => 'AND',
            'condition' => $this->escapeIdentifier($column) . ' ' . $operator . ' ' . $placeholder
        ];
        $this->bindings[$placeholder] = $value;
        return $this;
    }

    /**
     * Add WHERE condition (OR)
     */
    public function orWhere(string $column, $operator, $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $placeholder = ':where_' . count($this->bindings);
        $this->whereConditions[] = [
            'type' => 'OR',
            'condition' => $this->escapeIdentifier($column) . ' ' . $operator . ' ' . $placeholder
        ];
        $this->bindings[$placeholder] = $value;
        return $this;
    }

    /**
     * Add WHERE IN condition
     */
    public function whereIn(string $column, array $values): self
    {
        $placeholders = [];
        foreach ($values as $i => $value) {
            $placeholder = ':wherein_' . count($this->bindings);
            $placeholders[] = $placeholder;
            $this->bindings[$placeholder] = $value;
        }

        $this->whereConditions[] = [
            'type' => 'AND',
            'condition' => $this->escapeIdentifier($column) . ' IN (' . implode(', ', $placeholders) . ')'
        ];
        return $this;
    }

    /**
     * Add WHERE NULL condition
     */
    public function whereNull(string $column): self
    {
        $this->whereConditions[] = [
            'type' => 'AND',
            'condition' => $this->escapeIdentifier($column) . ' IS NULL'
        ];
        return $this;
    }

    /**
     * Add WHERE NOT NULL condition
     */
    public function whereNotNull(string $column): self
    {
        $this->whereConditions[] = [
            'type' => 'AND',
            'condition' => $this->escapeIdentifier($column) . ' IS NOT NULL'
        ];
        return $this;
    }

    /**
     * Add INNER JOIN
     */
    public function join(string $table, string $first, string $operator, string $second): self
    {
        $this->joins[] = "INNER JOIN {$table} ON {$first} {$operator} {$second}";
        return $this;
    }

    /**
     * Add LEFT JOIN
     */
    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        $this->joins[] = "LEFT JOIN {$table} ON {$first} {$operator} {$second}";
        return $this;
    }

    /**
     * Add ORDER BY
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        $this->orderBy[] = $this->escapeIdentifier($column) . ' ' . $direction;
        return $this;
    }

    /**
     * Set LIMIT
     */
    public function limit(int $limit): self
    {
        $this->limitValue = max(0, $limit);
        return $this;
    }

    /**
     * Set OFFSET
     */
    public function offset(int $offset): self
    {
        $this->offsetValue = max(0, $offset);
        return $this;
    }

    /**
     * Shorthand for limit + offset (pagination)
     */
    public function take(int $limit, int $offset = 0): self
    {
        return $this->limit($limit)->offset($offset);
    }

    // ==================== QUERY EXECUTION ====================

    /**
     * Get all records matching query
     */
    public function get(): array
    {
        $sql = $this->buildSelectQuery();
        $result = $this->db->fetchAll($sql, $this->bindings);
        $this->resetQuery();
        return $result;
    }

    /**
     * Get first record matching query
     */
    public function first(): ?array
    {
        $this->limitValue = 1;
        $result = $this->get();
        return $result[0] ?? null;
    }

    /**
     * Find by primary key
     */
    public function find($id): ?array
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /**
     * Get all records from table
     */
    public function all(): array
    {
        return $this->get();
    }

    /**
     * Count records
     */
    public function count(): int
    {
        $this->selectColumns = 'COUNT(*) as count';
        $result = $this->first();
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Check if records exist
     */
    public function exists(): bool
    {
        return $this->count() > 0;
    }

    // ==================== CRUD OPERATIONS ====================

    /**
     * Insert new record
     * @param array|object $data Key-value pairs or DTO to insert
     * @return int Last insert ID
     */
    public function insert(array|object $data): int
    {
        $data = $this->normalizeData($data);
        $data = $this->filterFillable($data);
        $columns = array_keys($data);
        $placeholders = [];
        $bindings = [];

        foreach ($data as $column => $value) {
            $placeholder = ':' . $column;
            $placeholders[] = $placeholder;
            $bindings[$placeholder] = $value;
        }

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', array_map([$this, 'escapeIdentifier'], $columns)),
            implode(', ', $placeholders)
        );

        $this->db->execute($sql, $bindings);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Create and return the new record
     */
    public function create(array $data): ?array
    {
        $id = $this->insert($data);
        return $this->find($id);
    }

    /**
     * Update records matching query
     * @param array|object $data Key-value pairs or DTO to update
     * @return int Number of affected rows
     */
    public function update(array|object $data): int
    {
        $data = $this->normalizeData($data);
        $data = $this->filterFillable($data);
        $setParts = [];

        foreach ($data as $column => $value) {
            $placeholder = ':set_' . $column;
            $setParts[] = $this->escapeIdentifier($column) . ' = ' . $placeholder;
            $this->bindings[$placeholder] = $value;
        }

        $sql = sprintf(
            "UPDATE %s SET %s%s",
            $this->table,
            implode(', ', $setParts),
            $this->buildWhereClause()
        );

        $stmt = $this->db->execute($sql, $this->bindings);
        $this->resetQuery();
        return $stmt->rowCount();
    }

    /**
     * Delete records matching query
     * @return int Number of affected rows
     */
    public function delete(): int
    {
        if (empty($this->whereConditions)) {
            throw new \Exception("Delete requires at least one WHERE condition for safety");
        }

        $sql = sprintf(
            "DELETE FROM %s%s",
            $this->table,
            $this->buildWhereClause()
        );

        $stmt = $this->db->execute($sql, $this->bindings);
        $this->resetQuery();
        return $stmt->rowCount();
    }

    /**
     * Delete by primary key
     */
    public function destroy($id): int
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    // ==================== QUERY BUILDING HELPERS ====================

    /**
     * Build full SELECT query
     */
    protected function buildSelectQuery(): string
    {
        $sql = "SELECT {$this->selectColumns} FROM {$this->table}";

        // Add JOINs
        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        // Add WHERE
        $sql .= $this->buildWhereClause();

        // Add ORDER BY
        if (!empty($this->orderBy)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }

        // Add LIMIT
        if ($this->limitValue !== null) {
            $sql .= ' LIMIT ' . $this->limitValue;
        }

        // Add OFFSET
        if ($this->offsetValue !== null) {
            $sql .= ' OFFSET ' . $this->offsetValue;
        }

        return $sql;
    }

    /**
     * Build WHERE clause
     */
    protected function buildWhereClause(): string
    {
        if (empty($this->whereConditions)) {
            return '';
        }

        $where = ' WHERE ';
        foreach ($this->whereConditions as $i => $condition) {
            if ($i === 0) {
                $where .= $condition['condition'];
            } else {
                $where .= ' ' . $condition['type'] . ' ' . $condition['condition'];
            }
        }

        return $where;
    }

    /**
     * Escape SQL identifier (column/table name)
     */
    protected function escapeIdentifier(string $identifier): string
    {
        // Remove any existing backticks and add new ones
        $identifier = str_replace('`', '', $identifier);

        // Handle table.column format
        if (strpos($identifier, '.') !== false) {
            $parts = explode('.', $identifier);
            return implode('.', array_map(function ($part) {
                return '`' . $part . '`';
            }, $parts));
        }

        return '`' . $identifier . '`';
    }

    /**
     * Filter data to only include fillable fields
     */
    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Normalize DTO or array data to array
     * @param array|object $data
     * @return array
     */
    protected function normalizeData(array|object $data): array
    {
        if (is_array($data)) {
            return $data;
        }

        // If DTO has toArray() method
        if (method_exists($data, 'toArray')) {
            return $data->toArray();
        }

        // Fallback: convert public properties to array
        return get_object_vars($data);
    }

    /**
     * Hide sensitive fields from output
     */
    protected function hideFields(array $data): array
    {
        if (empty($this->hidden)) {
            return $data;
        }
        return array_diff_key($data, array_flip($this->hidden));
    }

    // ==================== RAW QUERIES (for complex queries) ====================

    /**
     * Execute raw SELECT query with bindings
     */
    protected function rawSelect(string $sql, array $bindings = []): array
    {
        return $this->db->fetchAll($sql, $bindings);
    }

    /**
     * Execute raw query with bindings
     */
    protected function rawQuery(string $sql, array $bindings = [])
    {
        return $this->db->execute($sql, $bindings);
    }

    /**
     * Get single result from raw query
     */
    protected function rawFirst(string $sql, array $bindings = []): ?array
    {
        return $this->db->fetchOne($sql, $bindings);
    }
}
