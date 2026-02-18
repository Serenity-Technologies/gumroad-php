<?php

namespace Gumroad\DTOs;

class EnableLicenseDTO extends BaseDTO
{
    public function __construct(
        public string $access_token,
        public string $product_id,
        public string $license_key
    ) {}

}