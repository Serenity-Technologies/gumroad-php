<?php

namespace Gumroad\DTOs;

class EnableLicenseDTO extends BaseDTO
{
    public string $access_token;
    public string $product_id;
    public string $license_key;
}