<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

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
