<?php

namespace Gumroad\DTOs;

class ResourceSubscriptionDTO extends BaseDTO
{
    public function __construct(
        public string $id,
        public string $resource_name,
        public string $post_url
    ) {}
}
