<?php

/**
 * Purchase DTO
 * Data Transfer Object for purchase records
 * 
 * @see \Purchase Model
 * @see \PurchaseController
 */
readonly class PurchaseDTO
{
    public function __construct(
        public int $id,
        public string $supplierName,
        public string $buyDate,
        public string $status = 'รอดำเนินการ',
    ) {
    }

    /**
     * Create from database array
     * @param array{id: int, supplier_name: string, buy_date: string, status?: string} $row
     */
    public static function fromArray(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            supplierName: $row['supplier_name'] ?? '',
            buyDate: $row['buy_date'] ?? '',
            status: $row['status'] ?? 'รอดำเนินการ',
        );
    }

    /**
     * Create collection from array of rows
     * @param array[] $rows
     * @return self[]
     */
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
            'supplier_name' => $this->supplierName,
            'buy_date' => $this->buyDate,
            'status' => $this->status,
        ];
    }
}
