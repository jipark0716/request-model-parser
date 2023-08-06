<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_PARAMETER)]
/**
 * @template T
 */
class Collect
{
    /**
     * @param class-string<T> $collect
     */
    public function __construct(
       public readonly string $collect
    ) {
    }
}
