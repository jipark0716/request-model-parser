<?php

namespace App\Factories;

use App\Attributes\FromQuery;
use Illuminate\Foundation\Http\FormRequest;

class QueryDtoFactory extends BaseRequestDtoFactory
{
    protected function getData(FormRequest $request): array
    {
        return $request->query->all();
    }

    protected function getFromAttribute(): string
    {
        return FromQuery::class;
    }
}
