<?php

namespace App\Attributes;

use Attribute;

abstract class HasFieldAttribute
{
    public function __construct(
        public readonly ?string $field = null
    ) {
    }
}
