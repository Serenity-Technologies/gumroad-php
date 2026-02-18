<?php

namespace Gumroad\DTOs;

class VariantOptionDTO extends BaseDTO
{
    public string $name;
    public int $price_difference;
    public ?array $purchasing_power_parity_prices;
    public bool $is_pay_what_you_want;
    public ?array $recurrence_prices;
}