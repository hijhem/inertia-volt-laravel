<?php

declare(strict_types=1);

namespace InertiaVolt\Tests\Feature;

use Exception;
use Inertia\Testing\AssertableInertia as Assert;
use InertiaVolt\Laravel\Facades\InertiaVolt;
use InertiaVolt\Tests\FeatureTestCase;

class InertiaVoltPageTest extends FeatureTestCase
{
    public function test_register_page_without_calling_render_function_fails(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Component WithoutRender can't be rendered. render() function has to be called within the component.");

        InertiaVolt::page('WithoutRender');
    }

    public function test_register_page_that_doesnt_exist(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Component InvalidComponent not found.");

        InertiaVolt::page('InvalidComponent');
    }

    public function test_valid_inertia_volt_component(): void
    {
        InertiaVolt::page('WithRender');

        $this->get(route('volt'))->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
            ->component('WithRender.inertia', false)
            ->has('message')
            ->where('message', 'Hello from Inertia Volt'),
            );
    }

    public function test_valid_inertia_volt_component_with_prefix(): void
    {
        InertiaVolt::page('WithRender')->prefix('prefix')->name('prefix.');

        $this->get(route('prefix.volt'))->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
            ->component('WithRender.inertia', false)
            ->has('message')
            ->where('message', 'Hello from Inertia Volt'),
            );
    }
}
