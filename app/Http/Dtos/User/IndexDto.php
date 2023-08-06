<?php

namespace App\Http\Dtos\User;

use App\Attributes\Collect;

class IndexDto
{
    public readonly string $name;
    public readonly int $id;
    public readonly bool $hide;

    /**
     * @var string[] $parents
     */
    #[Collect(Tag::class)]
    public readonly array $tags;
}
