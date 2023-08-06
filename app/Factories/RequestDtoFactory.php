<?php

namespace App\Factories;

use App\Attributes\FromBody;
use App\Attributes\FromHeader;
use App\Attributes\FromQuery;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionParameter;
use ReflectionClass;

class RequestDtoFactory
{
    public function __construct(
        private readonly QueryDtoFactory $queryDtoFactory,
        private readonly BodyDtoFactory $bodyDtoFactory,
        private readonly HeaderDtoFactory $headerDtoFactory,
    ) {
    }

    /**
     * @throws \ReflectionException
     */
    public function createFromRequest(ReflectionParameter $parameter, FormRequest $request)
    {
        $classType = new ReflectionClass($parameter->getType()->getName());
        $result = $classType->newInstanceWithoutConstructor();
        foreach ($classType->getProperties() as $property) {
            if (!is_null($attribute = get_attribute($property, FromQuery::class))) {
                $property->setValue(
                    $result,
                    $this->queryDtoFactory->createFromRequest($property, $attribute, $request));
            }
            if (!is_null($attribute = get_attribute($property, FromBody::class))) {
                $property->setValue(
                    $result,
                    $this->bodyDtoFactory->createFromRequest($property, $attribute, $request));
            }
            if (!is_null($attribute = get_attribute($property, FromHeader::class))) {
                $property->setValue(
                    $result,
                    $this->headerDtoFactory->createFromRequest($property, $attribute, $request));
            }
        }
        return $result;
    }
}
