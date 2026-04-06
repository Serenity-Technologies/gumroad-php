<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\Facades;

use Illuminate\Support\Facades\Facade;

class Gumroad extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return 'gumroad';
    }
}