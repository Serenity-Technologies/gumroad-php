<?php

namespace Gumroad\DTOs;

class PayoutResponseDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        public PayoutDTO $payout
    ) {}
}
