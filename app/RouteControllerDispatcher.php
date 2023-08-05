<?php

namespace App;

use App\Attributes\FromQuery;
use App\Factories\RequestDtoFactory;
use App\Http\Dtos\User\Dto;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\ControllerDispatcher;
use ReflectionParameter;

class RouteControllerDispatcher extends ControllerDispatcher
{
    public function __construct(
        Container $container,
        private readonly RequestDtoFactory $requestDtoFactory,
    ) {
        parent::__construct($container);
    }

    /**
     * @param ReflectionParameter $parameter
     * @param $parameters
     * @param $skippableValue
     * @return mixed|object|null
     * @throws BindingResolutionException
     */
    protected function transformDependency(ReflectionParameter $parameter, $parameters, $skippableValue): mixed
    {
        $type = $parameter->getType();

        if (!is_null($attribute = get_attribute($parameter, FromQuery::class))) {
            return $this->requestDtoFactory->createFromRequest($type, $attribute, $this->container->make(FormRequest::class));
        }
        return parent::transformDependency($parameter, $parameters, $skippableValue);
    }
}
