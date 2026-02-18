<?php

namespace Gumroad\DTOs;

class OfferCodeDTO extends BaseDTO
{
    public string $id;
    public string $name;
    public ?int $amount_cents;
    public ?int $percent_off;
    public ?int $max_purchase_count;
    public bool $universal;
    public int $times_used;
}


