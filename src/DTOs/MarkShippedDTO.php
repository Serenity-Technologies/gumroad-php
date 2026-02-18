<?php

namespace Gumroad\DTOs;

class MarkShippedDTO extends BaseDTO
{
    public function __construct(
        public ?string $tracking_url
    ) {}

}