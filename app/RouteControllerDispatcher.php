<?php

namespace App;

use App\Attributes\FromBody;
use App\Attributes\FromHeader;
use App\Attributes\FromQuery;
use App\Attributes\FromRequest;
use App\Factories\BodyDtoFactory;
use App\Factories\HeaderDtoFactory;
use App\Factories\QueryDtoFactory;
use App\Factories\RequestDtoFactory;
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
        private readonly RequestDtoFactory $requestDtoFactory,
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
        if (!is_null($attribute = get_attribute($parameter, FromQuery::class))) {
            return $this->queryDtoFactory->createFromRequest($parameter, $attribute, $this->container->make(FormRequest::class));
        }
        if (!is_null($attribute = get_attribute($parameter, FromBody::class))) {
            return $this->bodyDtoFactory->createFromRequest($parameter, $attribute, $this->container->make(FormRequest::class));
        }
        if (!is_null($attribute = get_attribute($parameter, FromHeader::class))) {
            return $this->headerDtoFactory->createFromRequest($parameter, $attribute, $this->container->make(FormRequest::class));
        }
        if (!is_null(get_attribute($parameter, FromRequest::class))) {
            return $this->requestDtoFactory->createFromRequest($parameter, $this->container->make(FormRequest::class));
        }
        return parent::transformDependency($parameter, $parameters, $skippableValue);
    }
}
