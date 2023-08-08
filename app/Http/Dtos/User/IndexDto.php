<?php

namespace App\Http\Dtos\User;

use App\Attributes\Collect;
use App\Attributes\Validate;
use App\Rules\StartWith;

class IndexDto
{
    public readonly string $name;
    public readonly int $id;
    public readonly bool $hide;

    /**
     * @var Tag[] $tags
     */
    #[Collect(Tag::class)]
    public readonly array $tags;
}
