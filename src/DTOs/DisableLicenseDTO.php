<?php

namespace Gumroad\DTOs;

class DisableLicenseDTO extends BaseDTO
{
    public function __construct(
        public ?string $license_id,
        public string $product_id,
        public string $license_key
    ) {}

}