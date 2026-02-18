<?php

namespace Gumroad\DTOs;

class OfferCodeListDTO extends BaseDTO
{
    public bool $success;

    /** @var OfferCodeDTO[] */
    public array $offer_codes;
}