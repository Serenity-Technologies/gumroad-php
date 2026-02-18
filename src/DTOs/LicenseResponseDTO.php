<?php

namespace Gumroad\DTOs;

class LicenseResponseDTO extends BaseDTO
{
    public bool $success;
    public int $uses;
    public ?PurchaseDTO $purchase;
}