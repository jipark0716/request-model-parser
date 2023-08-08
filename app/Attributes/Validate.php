<?php

namespace App\Attributes;

use Illuminate\Contracts\Validation\ValidationRule;
use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER|Attribute::TARGET_PROPERTY)]
class Validate
{
    /**
     * @param string|ValidationRule $rule
     */
    public function __construct(
        public readonly string|ValidationRule $rule
    ) {
    }
}
