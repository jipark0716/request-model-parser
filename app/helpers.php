<?php

/**
 * @param ReflectionParameter|ReflectionProperty $parameter
 * @param string $class
 * @return mixed|null
 */
function get_attribute(ReflectionParameter|ReflectionProperty $parameter, string $class): ?object
{
    $attribute = $parameter->getAttributes($class)[0] ?? null;
    if (is_null($attribute)) return null;
    return new $class(...$attribute->getArguments());
}
