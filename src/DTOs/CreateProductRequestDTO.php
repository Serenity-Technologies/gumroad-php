<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

use Gumroad\Enums\CurrencyCode;
use Gumroad\Enums\ProductNativeType;
use Gumroad\Enums\RecurrenceId;

class CreateProductRequestDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $price,
        public ?CurrencyCode $price_currency_type,
        public ?ProductNativeType $native_type,
        public ?bool $is_physical,
        public ?bool $is_recurring_billing,
        public ?RecurrenceId $subscription_duration = null,
        public ?string $description = null,
        public ?string $custom_summary = null,
        public ?string $ai_prompt = '',
        public ?int $number_of_content_pages = null,
        public ?string $release_at_date = null,
    ) {}
}
