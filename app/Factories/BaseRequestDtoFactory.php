<?php

namespace App\Factories;

use App\Attributes\FromQuery;
use App\Attributes\HasFieldAttribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use ReflectionException;
use ReflectionNamedType;
use ReflectionClass;
use ReflectionProperty;

abstract class BaseRequestDtoFactory
{
    /**
     * @param ReflectionNamedType $type
     * @param FromQuery $attribute
     * @param FormRequest $request
     * @return mixed
     * @throws ReflectionException
     */
    public function createFromRequest(ReflectionNamedType $type, HasFieldAttribute $attribute, FormRequest $request): mixed
    {
        $data = $this->getData($request);
        if (!is_null($attribute->field)) {
            $data = $data->get($attribute->field);
        }
        return $this->create($type, $data);
    }

    protected abstract function getData(FormRequest $request): Collection;

    /**
     * @param ReflectionNamedType $type
     * @param Collection $data
     * @return mixed
     * @throws ReflectionException
     */
    protected function create(ReflectionNamedType $type, mixed $data): mixed
    {
        switch ($name = $type->getName()) {
            case 'int':
            case 'string':
            case 'bool':
                return $data;
//            case 'array':
//                return $data->map(fn ($row) => (
//                    $this->create()
//                ));
            default:
                $typeClass = new ReflectionClass($name);
                $result = $typeClass->newInstanceWithoutConstructor();
                foreach ($typeClass->getProperties() as $property) {
                    $fieldName = $this->getFieldName($property);
                    if ($data->has($fieldName)) {
                        $property->setValue($result, $this->create($property->getType(), $data->get($fieldName)));
                    }
                }
                return $result;
        }
    }

    protected abstract function getFieldName(ReflectionProperty $property): string;
//
//    /**
//     * @param Collection $data
//     * @param ReflectionClass $reflectClass
//     * @return void
//     * @throws ValidationException
//     */
//    protected function validate(Collection $data, ReflectionClass $reflectClass)
//    {
//        Facades\Validator::make(
//            $data->toArray(),
//            $this->rules($data, $reflectClass),
//        )->validate();
//    }
//
//    protected function rules(Collection $data, ReflectionClass $reflectClass): array
//    {
//        return collect($reflectClass->getProperties())
//            ->mapWithKeys(fn(ReflectionProperty $property): array => [
//                $property->getName() => $this->rulesByAttribute($property, true)
//            ])
//            ->toArray();
//    }
//
//    /**
//     * @param ReflectionProperty $property
//     * @return array
//     * @throws \Exception
//     */
//    protected function rulesByAttribute(ReflectionProperty $property): array
//    {
//        $result = [$this->getTypeRule($property)];
//
//        if (!$property->getType()->allowsNull() && !$property->hasDefaultValue()) {
//            $result[] = 'required';
//        }
//
//        return $result;
//    }
//
//    /**
//     * @param ReflectionProperty $property
//     * @return string
//     * @throws \Exception
//     */
//    protected function getTypeRule(ReflectionProperty $property)
//    {
//        return match ($property->getType()->getName()) {
//            'string' => 'string',
//            'int' => 'integer',
//            'bool' => 'boolean',
//            default => throw new \Exception($property->getType() . ' type is not support')
//        };
//    }
}
