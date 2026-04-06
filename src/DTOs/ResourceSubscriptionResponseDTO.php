<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

class ResourceSubscriptionResponseDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        public ResourceSubscriptionDTO $resource_subscription
    ) {}
}
