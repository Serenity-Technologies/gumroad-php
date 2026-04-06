<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

class CreateOfferCodeDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public ?int $amount_off,
        public ?int $percent_off,
        public string $offer_type,
        public ?int $max_purchase_count,
        public ?bool $universal,
    ) {}
}