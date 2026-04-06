<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

class LicenseResponseDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        public int $uses,
        public ?PurchaseDTO $purchase
    ) {}

}