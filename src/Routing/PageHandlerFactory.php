<?php

declare(strict_types=1);

namespace InertiaVolt\Laravel\Routing;

use Closure;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;

class PageHandlerFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {}

    /**
     * @return array{Closure,ReflectionFunctionAbstract}
     */
    public function createHandler(Closure|string $handler): array
    {
        if (!is_string($handler)) {
            return [$handler, new ReflectionFunction($handler)];
        }

        if (!class_exists($handler)) {
            throw new Exception('Handler is not a callable or invokable class.');
        }

        $handler = $this->container->get($handler);

        if (! is_callable($handler)) {
            throw new Exception('Handler is not a callable or invokable class.');
        }

        return [$handler, new ReflectionMethod($handler, '__invoke')];
    }
}
