<?php

declare(strict_types=1);

namespace InertiaVolt\Laravel;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as RouteItem;
use Illuminate\Support\Facades\Route;
use InertiaVolt\Laravel\Routing\VoltRequestHandler;

function render(string $uri, Closure|string $handler): RouteItem
{
    $component = app(PageContext::class)->component();

    return Route::get($uri, static fn(Request $request) => app(VoltRequestHandler::class)->handle($request, $component, $handler));
}

function post(string $uri, Closure|string $handler): RouteItem
{
    return Route::post($uri, $handler);
}

function put(string $uri, Closure|string $handler): RouteItem
{
    return Route::put($uri, $handler);
}

function delete(string $uri, Closure|string $handler): RouteItem
{
    return Route::delete($uri, $handler);
}
