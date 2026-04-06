<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

class VariantOptionDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public int $price_difference,
        public ?array $purchasing_power_parity_prices,
        public bool $is_pay_what_you_want,
        public ?array $recurrence_prices
    ) {}

}