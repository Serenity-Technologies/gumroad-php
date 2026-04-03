<?php

namespace Gumroad\DTOs;

class ResourceSubscriptionListDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        public array $resource_subscriptions
    ) {}
}
