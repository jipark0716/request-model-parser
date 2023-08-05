<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_PARAMETER)]
class FromQuery
{
    public function __construct(
        public readonly ?string $field = null
    ) {
    }
}
