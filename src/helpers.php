<?php

declare(strict_types=1);

if (! function_exists('resolve_inertia_component')) {
    /**
     * @param array{component: string} $page
     */
    function resolve_inertia_component(array $page): string
    {
        $pagePath = config()->string('inertia-volt.path');
        $extension = config()->string('inertia-volt.extension');

        return "$pagePath/{$page['component']}.$extension";
    }
}
