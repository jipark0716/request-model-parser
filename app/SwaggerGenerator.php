<?php

namespace App;

use L5Swagger\Generator;
use OpenApi\Generator as OpenApiGenerator;

class SwaggerGenerator extends Generator
{
    protected function createOpenApiGenerator(): OpenApiGenerator
    {
        return parent::createOpenApiGenerator()
            ->addProcessor(new SwaggerRoutePathBuildProcessor());
    }
}
