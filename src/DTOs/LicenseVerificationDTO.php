<?php

namespace Gumroad\DTOs;

class LicenseVerificationDTO extends BaseDTO
{
    public bool $success;
    public int $uses;
    public PurchaseDTO $purchase;
}