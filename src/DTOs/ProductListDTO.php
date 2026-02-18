<?php

namespace Gumroad\DTOs;

use Spatie\LaravelData\Attributes\DataCollectionOf;

class ProductListDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        #[DataCollectionOf(ProductDTO::class)]
        public array $products,
    ) {}
}
