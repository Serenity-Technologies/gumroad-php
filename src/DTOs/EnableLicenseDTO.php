<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

class EnableLicenseDTO extends BaseDTO
{
    public function __construct(
        public string $access_token,
        public string $product_id,
        public string $license_key
    ) {}

}