<?php

namespace Gumroad\DTOs;

class UserDTO extends BaseDTO
{
    public function __construct(
        public string $user_id,
        public string $name,
        public string $email,
        public ?string $bio,
        public ?string $twitter_handle,
        public string $url,
    ) {}
}
