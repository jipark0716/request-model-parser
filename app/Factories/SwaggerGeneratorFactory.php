<?php

namespace App\Factories;

use App\SwaggerGenerator;
use L5Swagger\ConfigFactory;
use L5Swagger\Generator;
use L5Swagger\GeneratorFactory;
use L5Swagger\SecurityDefinitions;

class SwaggerGeneratorFactory extends GeneratorFactory
{
    public function __construct(
        private readonly ConfigFactory $configFactory
    ) {
        parent::__construct($configFactory);
    }

    public function make(string $documentation): Generator
    {
        $config = $this->configFactory->documentationConfig($documentation);

        $paths = $config['paths'];
        $scanOptions = $config['scanOptions'] ?? [];
        $constants = $config['constants'] ?? [];
        $yamlCopyRequired = $config['generate_yaml_copy'] ?? false;

        $secSchemesConfig = $config['securityDefinitions']['securitySchemes'] ?? [];
        $secConfig = $config['securityDefinitions']['security'] ?? [];

        $security = new SecurityDefinitions($secSchemesConfig, $secConfig);

        return new SwaggerGenerator(
            $paths,
            $constants,
            $yamlCopyRequired,
            $security,
            $scanOptions
        );
    }
}
