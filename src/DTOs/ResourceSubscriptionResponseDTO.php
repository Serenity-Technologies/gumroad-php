<?php

namespace Gumroad\DTOs;

class ResourceSubscriptionResponseDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        public ResourceSubscriptionDTO $resource_subscription
    ) {}
}
