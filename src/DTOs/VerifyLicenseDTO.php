<?php

namespace Gumroad\DTOs;

class VerifyLicenseDTO extends BaseDTO
{
    public string $product_id;
    public string $license_key;
    public ?bool $increment_uses_count;
}