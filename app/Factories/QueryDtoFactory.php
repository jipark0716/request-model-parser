<?php

namespace App\Factories;

use App\Attributes\FromQuery;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionProperty;

class QueryDtoFactory extends BaseRequestDtoFactory
{
    protected function getData(FormRequest $request): array
    {
        return $request->query->all();
    }

    protected function getFieldName(ReflectionProperty $property): string
    {
        return get_attribute($property, FromQuery::class)?->field ?? $property->getName();
    }
}
