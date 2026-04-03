<?php

namespace Gumroad\DTOs;

class CreateResourceSubscriptionDTO extends BaseDTO
{
    public function __construct(
        public string $resource_name,
        public string $post_url
    ) {}
}
