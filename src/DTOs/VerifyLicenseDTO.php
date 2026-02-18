<?php

namespace Gumroad\DTOs;

class VerifyLicenseDTO extends BaseDTO
{
    public function __construct(
        public string $product_id,
        public string $license_key,
        public ?bool $increment_uses_count,
    ) {}
}