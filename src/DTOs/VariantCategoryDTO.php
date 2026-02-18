<?php

namespace Gumroad\DTOs;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;

class VariantCategoryDTO extends BaseDTO
{
    public string $id;
    public string $title;

    #[CastWith(ArrayCaster::class, itemType: VariantOptionDTO::class)]
    public array $options;
}


