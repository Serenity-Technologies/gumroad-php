<?php

namespace Gumroad\DTOs;

class LicenseVerificationDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        public int $uses,
        public PurchaseDTO $purchase,
    ) {}
}