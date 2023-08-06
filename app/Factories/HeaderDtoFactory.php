<?php

namespace App\Factories;

use App\Attributes\FromHeader;
use ReflectionProperty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class HeaderDtoFactory extends BaseRequestDtoFactory
{
    protected function getData(FormRequest $request): array
    {
        return collect($request->headers)->map(fn(array $row): string => $row[0])->toArray();
    }

    protected function getFieldName(ReflectionProperty $property): string
    {
        return get_attribute($property, FromHeader::class)?->field ?? $property->getName();
    }
}
