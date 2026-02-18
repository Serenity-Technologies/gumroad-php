<?php

namespace Gumroad\DTOs;

class SaleResponseDTO extends BaseDTO
{
    public bool $success;
    public SaleDTO $sale;
}