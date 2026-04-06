<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

use Spatie\LaravelData\Attributes\DataCollectionOf;

class OfferCodeListDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        #[DataCollectionOf(OfferCodeDTO::class)]
        public array $offer_codes
    ) {}
}
