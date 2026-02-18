<?php

namespace Gumroad\DTOs;

class RefundSaleDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        public string $message
    ) {}

}