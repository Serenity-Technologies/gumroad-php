<?php

namespace Gumroad\DTOs;

class UserResponseDTO extends BaseDTO
{
    public bool $success;
    public UserDTO $user;
}