<?php

namespace Gumroad\DTOs;

class PayoutListDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        public array $payouts,
        public ?string $next_page_url = null,
        public ?string $next_page_key = null
    ) {}
}
