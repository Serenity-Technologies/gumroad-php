<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\Enums;

enum ProductNativeType: string
{
    case DIGITAL = 'digital';
    case COURSE = 'course';
    case EBOOK = 'ebook';
    case NEWSLETTER = 'newsletter';
    case MEMBERSHIP = 'membership';
    case PODCAST = 'podcast';
    case AUDIOBOOK = 'audiobook';
    case PHYSICAL = 'physical';
    case BUNDLE = 'bundle';
}
