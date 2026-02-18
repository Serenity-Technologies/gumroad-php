<?php

namespace Gumroad\DTOs;

class VariantDTO extends BaseDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public ?int $price_difference_cents,
        public ?int $max_purchase_count,
        public ?array $purchasing_power_parity_prices,
        public bool $is_pay_what_you_want,
        public ?array $recurrence_prices,
    ) {}
}
