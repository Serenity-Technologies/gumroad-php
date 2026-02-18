<?php

namespace Gumroad\DTOs;

use Spatie\LaravelData\Attributes\DataCollectionOf;

class VariantCategoryDTO extends BaseDTO
{
    public function __construct(
        public string $id,
        public string $title,
        #[DataCollectionOf(VariantOptionDTO::class)]
        public array $options,
    ) {}
}
