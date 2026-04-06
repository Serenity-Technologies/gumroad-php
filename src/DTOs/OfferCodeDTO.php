<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

class OfferCodeDTO extends BaseDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public ?int $amount_cents,
        public ?int $percent_off,
        public ?int $max_purchase_count,
        public bool $universal,
        public int $times_used,
    ) {}
}


