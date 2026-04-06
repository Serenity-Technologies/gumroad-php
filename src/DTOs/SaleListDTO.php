<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

use Spatie\LaravelData\Attributes\DataCollectionOf;

class SaleListDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        public ?string $next_page_url,
        public ?string $next_page_key,
        #[DataCollectionOf(SaleDTO::class)]
        public array $sales
    ) {}
}
