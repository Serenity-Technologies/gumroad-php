<?php

namespace Gumroad\DTOs;

use Spatie\LaravelData\Attributes\DataCollectionOf;

class SaleListDTO extends BaseDTO
{
    public bool $success;
    public ?string $next_page_url;
    public ?string $next_page_key;

    #[DataCollectionOf(SaleDTO::class)]
    public array $sales;
}
