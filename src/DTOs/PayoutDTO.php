<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

class PayoutDTO extends BaseDTO
{
    public function __construct(
        public ?string $id,
        public string $amount,
        public string $currency,
        public string $status,
        public string $created_at,
        public ?string $processed_at,
        public string $payment_processor,
        public ?string $bank_account_visual,
        public ?string $paypal_email,
        public ?array $sales = null,
        public ?array $refunded_sales = null,
        public ?array $disputed_sales = null,
        public ?array $transactions = null
    ) {}
}
