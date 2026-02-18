<?php

namespace Gumroad\DTOs;

class LicenseDTO extends BaseDTO
{
    public function __construct(
        public string $license_key,
        public string $product_id,
        public ?string $license_id,
        public bool $license_disabled,
        public int $uses
    ) {}

}
