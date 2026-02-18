<?php

namespace Gumroad\DTOs;

class UserResponseDTO extends BaseDTO
{
    public function __construct(
        public bool $success,
        public UserDTO $user,
    ) {}
}