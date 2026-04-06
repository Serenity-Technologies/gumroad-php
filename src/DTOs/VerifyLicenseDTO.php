<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

class VerifyLicenseDTO extends BaseDTO
{
    public function __construct(
        public string $product_id,
        public string $license_key,
        public ?bool $increment_uses_count,
    ) {}
}