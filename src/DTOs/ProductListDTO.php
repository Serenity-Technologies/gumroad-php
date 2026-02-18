<?php

namespace Gumroad\DTOs;

class ProductListDTO extends BaseDTO
{
    public bool $success;
    
    /** @var ProductDTO[] */
    public array $products;
}