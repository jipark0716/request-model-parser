<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class Request extends FormRequest
{

    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     *
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function parse(string $class): object
    {
        $result = new $class;
        $reflectClass = new ReflectionClass($class);
        $this->createValidator($reflectClass)->validate();
        foreach ($reflectClass->getProperties() as $property) {
            if ($this->has($property->getName())) {
                $property->setValue($result, $this->get($property->getName()));
            }
        }
        dd($result);

        return $result;
    }

    /**
     * @param ReflectionClass $reflectClass
     * @return Validator
     */
    protected function createValidator(ReflectionClass $reflectClass): Validator
    {
        return Facades\Validator::make(
            $this->validationData(),
            collect($reflectClass->getProperties())
                ->mapWithKeys(fn(ReflectionProperty $property): array => [
                    $property->getName() => $this->getValidateRule($property)
                ])
                ->toArray()
        );
    }

    /**
     * @param ReflectionProperty $property
     * @return array
     * @throws \Exception
     */
    protected function getValidateRule(ReflectionProperty $property): array
    {
        $result = [];

        $result[] = match ($property->getType()->getName()) {
            'string' => 'string',
            'int' => 'integer',
            'bool', 'boolean' => 'boolean',
            default => throw new \Exception($property->getType() . ' type is not support')
        };

        if (!$property->getType()->allowsNull() && !$property->hasDefaultValue()) {
            $result[] = 'required';
        }

        return $result;
    }
}
