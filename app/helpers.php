<?php

/**
 * @template T
 * @param ReflectionParameter|ReflectionProperty $parameter
 * @param class-string<T> $class
 * @return ?T
 */
function get_attribute(ReflectionParameter|ReflectionProperty $parameter, string $class): ?object
{
    $attribute = $parameter->getAttributes($class)[0] ?? null;
    if (is_null($attribute)) return null;
    return new $class(...$attribute->getArguments());
}

/**
 * @template T
 * @param ReflectionParameter|ReflectionProperty $parameter
 * @param class-string<T> $class
 * @return T[]
 */
function get_attributes(ReflectionParameter|ReflectionProperty $parameter, string $class): array
{
    $attributes = $parameter->getAttributes($class);
    return collect($attributes)->map(fn ($attribute) => new $class(...$attribute->getArguments()))->toArray();
}
