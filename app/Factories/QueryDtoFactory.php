<?php

namespace App\Factories;

use App\Attributes\FromQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use ReflectionProperty;

class QueryDtoFactory extends BaseRequestDtoFactory
{
    protected function getData(FormRequest $request): Collection
    {
        return collect($request->query);
    }

    protected function getFieldName(ReflectionProperty $property): string
    {
        return get_attribute($property, FromQuery::class)?->field ?? $property->getName();
    }
}
