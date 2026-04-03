<?php

namespace Gumroad\DTOs;

class UpcomingPayoutsDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        public array $payouts
    ) {}
}
