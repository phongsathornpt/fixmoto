<?php

/**
 * Purchase Detail DTO
 * Contains full purchase information for detail view
 * 
 * @see \PurchaseController::show()
 */
readonly class PurchaseDetailDTO
{
    /**
     * @param PurchaseItemDTO[] $items
     */
    public function __construct(
        public int $id,
        public string $supplierName,
        public string $buyDate,
        public string $status,
        public float $totalCost,
        public bool $isPaid,
        public bool $isReceived,
        public array $items = [],
    ) {
    }
}
