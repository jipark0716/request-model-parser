<?php

namespace App;

use App\Http\Dtos\User\Dto;
use Illuminate\Container\Container;
use Illuminate\Routing\ControllerDispatcher;
use ReflectionParameter;

class RouteControllerDispatcher extends ControllerDispatcher
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * @param ReflectionParameter $parameter
     * @param $parameters
     * @param $skippableValue
     * @return mixed|object|null
     * @throws \ReflectionException
     */
    protected function transformDependency(ReflectionParameter $parameter, $parameters, $skippableValue): mixed
    {
        $type = $parameter->getType();

        if (!$type->isBuiltin()) {
            $parameterType = new \ReflectionClass($parameter->getType()->getName());
            if ($parameterType->isSubclassOf(Dto::class)) {
                dd(123);
            }
        }
        return parent::transformDependency($parameter, $parameters, $skippableValue);
    }
}
