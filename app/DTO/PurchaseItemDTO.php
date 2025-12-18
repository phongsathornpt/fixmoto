<?php

/**
 * Purchase Item DTO
 * Represents a line item in a purchase order
 */
readonly class PurchaseItemDTO
{
    public function __construct(
        public int $inventoryId,
        public string $name,
        public int $amount,
        public float $cost,
        public float $totalCost,
    ) {
    }

    public static function fromArray(array $row): self
    {
        $amount = (int) ($row['amount'] ?? 0);
        $cost = (float) ($row['cost'] ?? 0);

        return new self(
            inventoryId: (int) ($row['inventory_id'] ?? 0),
            name: $row['name'] ?? '',
            amount: $amount,
            cost: $cost,
            totalCost: $amount * $cost,
        );
    }

    public static function collection(array $rows): array
    {
        return array_map(fn($row) => self::fromArray($row), $rows);
    }
}
