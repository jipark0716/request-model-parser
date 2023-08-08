<?php

namespace App\Http\Dtos\User;

use App\Attributes\Validate;
use App\Rules\StartWith;

class Tag
{
    #[Validate(new StartWith('tag'))]
    public readonly string $tag;
}
