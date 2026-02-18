<?php

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
