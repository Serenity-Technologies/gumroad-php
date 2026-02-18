<?php

namespace Gumroad\DTOs;

class SaleResponseDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        public SaleDTO $sale
    ) {}

}