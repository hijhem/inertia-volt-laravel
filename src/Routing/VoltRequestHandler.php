<?php

declare(strict_types=1);

namespace InertiaVolt\Laravel\Routing;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VoltRequestHandler
{
    public function __construct(
        private PageHandlerFactory $pageHandlerFactory,
    ) {}

    public function handle(Request $request, string $component, Closure|string $handler): Response
    {
        [$handler, $reflectionFunction] = $this->pageHandlerFactory->createHandler($handler);

        /** @var \Illuminate\Routing\Route */
        $route = $request->route();

        $parameters = $route->resolveMethodDependencies(
            $route->parametersWithoutNulls(),
            $reflectionFunction,
        );

        $props = $handler(...array_values($parameters));

        return Inertia::render($component . '.inertia', $props);
    }
}
