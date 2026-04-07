<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\Enums;

enum RecurrenceId: string
{
    case BIANNUALLY = 'biannually';
    case EVERY_TWO_YEARS = 'every_two_years';
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case YEARLY = 'yearly';
}
