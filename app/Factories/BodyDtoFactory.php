<?php

namespace App\Factories;

use App\Attributes\FromBody;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionProperty;

class BodyDtoFactory extends BaseRequestDtoFactory
{
    protected function getData(FormRequest $request): array
    {
        return $request->json();
    }

    protected function getFieldName(ReflectionProperty $property): string
    {
        return get_attribute($property, FromBody::class)?->field ?? $property->getName();
    }
}
