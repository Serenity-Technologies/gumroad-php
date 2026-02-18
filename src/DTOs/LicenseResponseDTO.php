<?php

namespace Gumroad\DTOs;

class LicenseResponseDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        public int $uses,
        public ?PurchaseDTO $purchase
    ) {}

}