<?php

namespace Gumroad\Facades;

use Illuminate\Support\Facades\Facade;

class Gumroad extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return 'gumroad';
    }
}