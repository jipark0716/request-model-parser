<?php

namespace App\Factories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class QueryDtoFactory extends BaseRequestDtoFactory
{
    protected function getData(FormRequest $request): Collection
    {
        return collect($request->query);
    }
}
