<?php

namespace Gumroad\DTOs;

class SaleListDTO extends BaseDTO
{
    public bool $success;
    public ?string $next_page_url;
    public ?string $next_page_key;

    /** @var SaleDTO[] */
    public array $sales;
}