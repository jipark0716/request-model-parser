<?php

namespace App\Http\Dtos\User;

use App\Attributes\FromQuery;

class Request
{
    #[FromQuery]
    public readonly IndexDto $dto;
}
