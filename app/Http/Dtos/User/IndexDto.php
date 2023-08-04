<?php

namespace App\Http\Dtos\User;

class IndexDto implements Dto
{
    public string $name;
    public int $id;
    public bool $hide;
}
