<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\DTOs;

class CreateResourceSubscriptionDTO extends BaseDTO
{
    public function __construct(
        public string $resource_name,
        public string $post_url
    ) {}
}
