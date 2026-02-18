<?php

namespace Gumroad\DTOs;

class DecrementUsesDTO extends BaseDTO
{
    public function __construct(
        public string $product_id,
        public string $license_key
    ) {}

}