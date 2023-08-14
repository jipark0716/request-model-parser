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
        $routes = collect(Facades\Route::getRoutes());
        if (!is_null($this->namespaces)) {
            $routes = $routes->filter(
                fn (Route $route) =>
                    ($controller = $route->getControllerClass()) && $this->namespaces->first(
                        fn (string $namespace) =>
                        str_starts_with($controller, $namespace)
                    )
            );
        }
        $analysis->openapi->paths = array_merge(
            $analysis->openapi->paths,
            $routes->groupBy('uri')
                ->map(fn (Collection $routes) => $this->createPath($routes))
                ->toArray()
        );
    }

    /**
     * @param Collection<Route> $routes
     * @return PathItem
     */
    private function createPath(Collection $routes): PathItem
    {
        $arguments = [
            'path' => $routes->first()->uri,
        ];

        foreach (['get', 'put', 'post', 'delete', 'options', 'patch', 'trace'] as $method) {
            if ($route = $routes->firstWhere(fn (Route $route): bool => in_array(strtoupper($method), $route->methods))) {
                $arguments[$method] = $this->parseAction($route, $method);
            }
        }

        return new PathItem($arguments);
    }

    private function parseAction(Route $route, string $method): array
    {
        return [
            'tags' => [],
            'summary' => '',
            'operationId' => "[$method]/{$route->uri()}",
            'response' => [
                '200' => [
                    'description' => 'asdf',
                ]
            ]
        ];
    }
}
