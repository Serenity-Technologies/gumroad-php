<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

class UpdateOfferCodeDTO extends BaseDTO
{
    public function __construct(
        public ?int $max_purchase_count,
        public ?int $amount_off,
        public ?int $percent_off,
        public ?string $expiration_date
    ) {}

}