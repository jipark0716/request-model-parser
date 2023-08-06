<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_PARAMETER)]
abstract class HasFieldAttribute
{
    public function __construct(
        public readonly ?string $field = null
    ) {
    }
}
