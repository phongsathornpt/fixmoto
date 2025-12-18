<?php

/**
 * Supplier DTO
 */
readonly class SupplierDTO
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            name: $row['name'] ?? '',
        );
    }

    public static function collection(array $rows): array
    {
        return array_map(fn($row) => self::fromArray($row), $rows);
    }

    /**
     * Convert to array for Model insert/update
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
