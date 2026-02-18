<?php

namespace Gumroad\DTOs;

class UpdateOfferCodeDTO extends BaseDTO
{
    public ?int $max_purchase_count;
    public ?int $amount_off;
    public ?int $percent_off;
    public ?string $expiration_date;
}