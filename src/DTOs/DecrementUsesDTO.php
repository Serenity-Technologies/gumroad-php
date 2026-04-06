<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

class DecrementUsesDTO extends BaseDTO
{
    public function __construct(
        public string $product_id,
        public string $license_key
    ) {}

}