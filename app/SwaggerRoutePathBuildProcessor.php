<?php

namespace App;

use OpenApi\Analysis;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\PathItem;
use OpenApi\Context;
use OpenApi\Processors\ProcessorInterface;

class SwaggerRoutePathBuildProcessor implements ProcessorInterface
{
    public function __invoke(Analysis $analysis)
    {
        $operation = $analysis->unmerged()->getAnnotationsOfType(Operation::class);

        $analysis->openapi->paths[] = $this->createExample($operation);
    }

    private function createExample($operation): PathItem
    {
        return new PathItem(
            [
                'path' => 'asdf',
            ]
        );
    }
}
