<?php

namespace App\Factories;

use App\Attributes\Collect;
use App\Attributes\HasFieldAttribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use ReflectionParameter;
use ReflectionException;
use ReflectionClass;
use ReflectionProperty;

abstract class BaseRequestDtoFactory
{
    /**
     * @param ReflectionParameter $parameter
     * @param HasFieldAttribute $attribute
     * @param FormRequest $request
     * @return mixed
     * @throws ReflectionException
     */
    public function createFromRequest(ReflectionParameter $parameter, HasFieldAttribute $attribute, FormRequest $request): mixed
    {
        $data = $this->getData($request);
        if (!is_null($attribute->field)) {
            $data = $data[$attribute->field] ?? '';
        }
        return $this->create($parameter->getType()->getName(), $data, $parameter);
    }

    protected abstract function getData(FormRequest $request): array;

    /**
     * @param string $typeName
     * @param mixed $data
     * @param ReflectionProperty|ReflectionParameter|null $property
     * @return mixed
     * @throws ReflectionException
     */
    protected function create(string $typeName, mixed $data, ReflectionProperty|ReflectionParameter|null $property = null): mixed
    {
        switch ($typeName) {
            case 'int':
            case 'string':
            case 'bool':
                return $data;
            case 'array':
                if (is_null($property) || is_null($collect = get_attribute($property, Collect::Class))) {
                    return $data ?? [];
                }
                /**
                 * @var Collection $data
                 */
                return collect($data ?? [])->map(fn ($row) => $this->create($collect->collect, $row))->toArray();
            default:
                $typeClass = new ReflectionClass($typeName);
                $result = $typeClass->newInstanceWithoutConstructor();
                foreach ($typeClass->getProperties() as $property) {
                    $fieldName = $this->getFieldName($property);
                    $property->setValue(
                        $result,
                        $this->create(
                            $property->getType()->getName(),
                            $data[$fieldName] ?? null,
                            $property,
                        )
                    );
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
