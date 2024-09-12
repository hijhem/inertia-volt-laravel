<?php

declare(strict_types=1);

namespace InertiaVolt\Laravel\Routing;

use Exception;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Arr;
use InertiaVolt\Laravel\PageContext;

class PendingInertiaPageRegistration
{
    /**
     * @var array{name?: string, prefix?: string, middleware?: string[]|string}
     */
    protected array $attributes = [];

    public function __construct(
        protected PageContext $pageContext,
        protected RouteRegistrar $registrar,
        protected string $componentPath,
    ) {}

    public function name(string $name): self
    {
        $this->attributes['name'] = $name;

        return $this;
    }

    public function prefix(string $prefix): self
    {
        $this->attributes['prefix'] = $prefix;

        return $this;
    }

    /**
     * @param string[]|string $middleware
     */
    public function middleware(array|string $middleware): self
    {
        $this->attributes['middleware'] = Arr::wrap($middleware);

        return $this;
    }

    public function __destruct()
    {
        if (isset($this->attributes['middleware'])) {
            $this->registrar->middleware($this->attributes['middleware']);
        }

        if (isset($this->attributes['name'])) {
            $this->registrar->name($this->attributes['name']);
        }

        if (isset($this->attributes['prefix'])) {
            $this->registrar->prefix($this->attributes['prefix']);
        }

        $this->registrar->group(function () {
            try {
                ob_start();
                require $this->componentPath;

                if (! $this->pageContext->renderTriggered()) {
                    $component = $this->pageContext->component();
                    throw new Exception("Component $component can't be rendered. render() function has to be called within the component.");
                }

            } finally {
                ob_end_clean();

                $this->pageContext->flush();
            }
        });
    }
}
