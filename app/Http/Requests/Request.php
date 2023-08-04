<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades;
use Illuminate\Validation\ValidationException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class Request extends FormRequest
{
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

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
        $this->fillFromQuery($result, $reflectClass);

        return $result;
    }

    /**
     * @template T
     * @param T $result
     * @param ReflectionClass $reflectClass
     * @return T
     * @throws ValidationException
     */
    protected function fillFromQuery($result, ReflectionClass $reflectClass): object
    {
        Facades\Validator::make(
            $this->validationData(),
            collect($reflectClass->getProperties())
                ->mapWithKeys(fn(ReflectionProperty $property): array => [
                    $property->getName() => $this->rulesByAttribute($property, true)
                ])
                ->toArray()
        )->validate();

        foreach ($reflectClass->getProperties() as $property) {
            $name = $property->getName();
            if ($this->query->has($name)) {
                if ($property->getType()->getName() == 'bool') {
                    $property->setValue($result, $this->query->get($name) != 0);
                } else {
                    $property->setValue($result, $this->query->get($name));
                }
            }
        }

        return $result;
    }

    /**
     * @param ReflectionProperty $property
     * @param bool $fromQuery
     * @return array
     * @throws \Exception
     */
    protected function rulesByAttribute(ReflectionProperty $property, bool $fromQuery): array
    {
        $result = [];

        $result[] = match ($property->getType()->getName()) {
            'string' => 'string',
            'int' => 'integer',
            'bool' => $fromQuery ? 'in:0,1' : 'boolean',
            default => throw new \Exception($property->getType() . ' type is not support')
        };

        if (!$property->getType()->allowsNull() && !$property->hasDefaultValue()) {
            $result[] = 'required';
        }

        return $result;
    }
}
