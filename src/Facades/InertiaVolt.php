<?php

declare(strict_types=1);

namespace InertiaVolt\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \InertiaVolt\Laravel\Routing\PendingInertiaPageRegistration page(string $component)
 *
 * @see \InertiaVolt\Laravel\InertiaVoltManager::class
 */
class InertiaVolt extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \InertiaVolt\Laravel\InertiaVoltManager::class;
    }
}
