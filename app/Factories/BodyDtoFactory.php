<?php

namespace App\Factories;

use App\Attributes\FromBody;
use Illuminate\Foundation\Http\FormRequest;

class BodyDtoFactory extends BaseRequestDtoFactory
{
    protected function getData(FormRequest $request): array
    {
        return $request->json()->all();
    }

    protected function getFromAttribute(): string
    {
        return FromBody::class;
    }
}
