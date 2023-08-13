<?php

namespace App;

use Illuminate\Filesystem\Filesystem;
use L5Swagger\Generator;
use L5Swagger\SecurityDefinitions;
use OpenApi\Generator as OpenApiGenerator;

class SwaggerGenerator extends Generator
{
    /**
     * @var string[] $namespaces
     */
    private readonly array $namespaces;
    public function __construct(
        array $paths,
        array $constants,
        bool $yamlCopyRequired,
        SecurityDefinitions $security,
        array $scanOptions,
        ?Filesystem $filesystem = null
    ) {
        $this->namespaces = $paths['namespace'] ?? [];
        parent::__construct($paths, $constants, $yamlCopyRequired, $security, $scanOptions, $filesystem);
    }
        protected function createOpenApiGenerator(): OpenApiGenerator
    {
        return parent::createOpenApiGenerator()
            ->addProcessor(new SwaggerRoutePathBuildProcessor(collect($this->namespaces)));
    }
}
