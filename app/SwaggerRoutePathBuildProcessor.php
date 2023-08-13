<?php

namespace App;

use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades;
use OpenApi\Analysis;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\PathItem;
use OpenApi\Processors\ProcessorInterface;

class SwaggerRoutePathBuildProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Collection $namespaces,
    ) {
    }

    public function __invoke(Analysis $analysis): void
    {
        $operation = $analysis->unmerged()->getAnnotationsOfType(Operation::class);

        $routes = collect(Facades\Route::getRoutes());
        if (!is_null($this->namespaces)) {
            $routes = $routes->filter(
                fn (Route $route) =>
                    $this->namespaces->countBy(
                        fn (string $namespace) =>
                            str_starts_with($route->getControllerClass(), $namespace)
                    )
            );
        }
        dd($routes->map(fn (Route $route) => $route->uri())->join("\n"));

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
