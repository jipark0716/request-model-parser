<?php

namespace App\Factories;

use App\Attributes\FromHeader;
use Illuminate\Foundation\Http\FormRequest;

class HeaderDtoFactory extends BaseRequestDtoFactory
{
    protected function getData(FormRequest $request): array
    {
        return collect($request->headers)->map(fn(array $row): string => $row[0])->toArray();
    }

    protected function getFromAttribute(): string
    {
        return FromHeader::class;
    }
}
