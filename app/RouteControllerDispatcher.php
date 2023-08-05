<?php

namespace App;

use App\Attributes\FromBody;
use App\Attributes\FromHeader;
use App\Attributes\FromQuery;
use App\Factories\BodyDtoFactory;
use App\Factories\HeaderDtoFactory;
use App\Factories\QueryDtoFactory;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\ControllerDispatcher;
use ReflectionException;
use ReflectionParameter;

class RouteControllerDispatcher extends ControllerDispatcher
{
    public function __construct(
        Container $container,
        private readonly QueryDtoFactory $queryDtoFactory,
        private readonly BodyDtoFactory $bodyDtoFactory,
        private readonly HeaderDtoFactory $headerDtoFactory,
    ) {
        parent::__construct($container);
    }

    /**
     * @param ReflectionParameter $parameter
     * @param $parameters
     * @param $skippableValue
     * @return mixed
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    protected function transformDependency(ReflectionParameter $parameter, $parameters, $skippableValue): mixed
    {
        $type = $parameter->getType();

        if (!is_null($attribute = get_attribute($parameter, FromQuery::class))) {
            return $this->queryDtoFactory->createFromRequest($type, $attribute, $this->container->make(FormRequest::class));
        }
        if (!is_null($attribute = get_attribute($parameter, FromBody::class))) {
            return $this->bodyDtoFactory->createFromRequest($type, $attribute, $this->container->make(FormRequest::class));
        }
        if (!is_null($attribute = get_attribute($parameter, FromHeader::class))) {
            return $this->headerDtoFactory->createFromRequest($type, $attribute, $this->container->make(FormRequest::class));
        }
        return parent::transformDependency($parameter, $parameters, $skippableValue);
    }
}
