<?php

declare(strict_types=1);

namespace InertiaVolt\Tests;

use Illuminate\Support\Facades\View;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

abstract class FeatureTestCase extends TestbenchTestCase
{
    use WithWorkbench;

    protected function setUp(): void
    {
        parent::setUp();

        View::addLocation(__DIR__ . '/fixtures/resources/views');
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('inertia-volt.path', __DIR__ . '/fixtures/resources/js/Pages');
    }

    protected function getPackageProviders($app)
    {
        return [
            \InertiaVolt\Laravel\ServiceProvider::class,
            \Inertia\ServiceProvider::class,
        ];
    }
}
