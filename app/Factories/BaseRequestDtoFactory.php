<?php

namespace App\Factories;

use App\Attributes\Collect;
use App\Attributes\FromHeader;
use App\Attributes\HasFieldAttribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use ReflectionParameter;
use ReflectionException;
use ReflectionClass;
use ReflectionProperty;

abstract class BaseRequestDtoFactory
{
    protected abstract function getFromAttribute(): string;

    /**
     * @param ReflectionParameter|ReflectionProperty $parameter
     * @param HasFieldAttribute $attribute
     * @param FormRequest $request
     * @return mixed
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function createFromRequest(ReflectionParameter|ReflectionProperty $parameter, HasFieldAttribute $attribute, FormRequest $request): mixed
    {
        $fullData = $this->getData($request);
        $data = is_null($attribute->field) ? $fullData : $fullData[$attribute->field];

        $type = $parameter->getType()->getName();
        Validator::make($fullData, $this->rules($type, $parameter))->validate();
        return $this->create($type, $data, $parameter);
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
                return is_null($property) || is_null($collect = get_attribute($property, Collect::Class)) ?
                    $data ?? []:
                    collect($data ?? [])->map(fn ($row) => $this->create($collect->collect, $row))->toArray();
            default:
                $typeClass = new ReflectionClass($typeName);
                $result = $typeClass->newInstanceWithoutConstructor();
                collect($typeClass->getProperties())->each(fn (ReflectionProperty $property) =>
                $property->setValue(
                    $result,
                    $this->create(
                        $property->getType()->getName(),
                        $data[$this->getFieldName($property)] ?? null,
                        $property,
                    )));
                return $result;
        }
    }

    protected function getFieldName(ReflectionProperty $property): string
    {
        return get_attribute($property, $this->getFromAttribute())?->field ?? $property->getName();
    }

    /**
     * @param string $typeName
     * @param ReflectionProperty|ReflectionParameter|null $property
     * @param array $prefix
     * @return array|string[]
     * @throws ReflectionException
     */
    protected function rules(string $typeName, ReflectionProperty|ReflectionParameter|null $property = null, array $prefix = []): array
    {
        if (
            !is_null($property) && (
                is_null($attribute = get_attribute($property, $this->getFromAttribute())) ||
                !is_null($attribute->field))) {
            $prefix[] = $this->getFieldName($property);
        }
        $result = [];
        $field = join('.', $prefix);
        if (!is_null($property) && !$property->hasDefaultValue() && !$property->getType()->allowsNull()) {
            $result[$field] = ['required'];
        }
        switch ($typeName) {
            case 'int':
            case 'string':
                $result[$field][] = $typeName;
                break;
            case 'bool':
                break;
            case 'array':
                $result[$field][] = ['array'];
                if (!is_null($property) && !is_null($collect = get_attribute($property, Collect::Class))) {
                    $result = array_merge($result, $this->rules($collect->collect, null, array_merge($prefix, ['*'])));
                }
                break;
            default:
                $typeClass = new ReflectionClass($typeName);
                $result = collect($typeClass->getProperties())->mapWithKeys(fn (ReflectionProperty $property) =>
                    $this->rules($property->getType()->getName(), $property, $prefix)
                )->toArray();
                break;
        }
        return $result;
    }
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
