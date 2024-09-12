# Inertia Volt

Full-stack page components powered by Inertia.JS

**Note: This package is in an early alpha stage, so expect potential bugs and API-breaking changes!**

```
// Welcome.inertia.vue

<?php

use function InertiaVolt\Laravel\{render};

render('welcome/{name}', fn(string $name) => [
    'name' => $name,
]);

?>

<script setup lang="ts">
defineProps<{ name: string }();
</script>

<template>
    <h1>Hello, {{ name }}!</h1>
</template>
```

## Getting Started

### Installation

Install the Laravel plugin. The service provider and default configuration are registered automatically:

```
composer require inertia-volt/laravel
```

Next, install the Vite plugin

```
npm add @inertia-volt/vite
// or pnpm
pnpm add @inertia-volt/vite
// or bun
bun add @inertia-volt/vite
```

In your `vite.config.js` file, import the `inertiaVolt` plugin and add it to the `plugin` section.

```
// vite.config.js

import inertiaVolt from '@inertia-volt/vite'

export default defineConfig({
    plugins: [
        laravel(...),
        inertiaVolt({
            path: 'resources/js/Pages',
            extension: 'vue' // or tsx/jsx/svelte
        }),
        vue(...),
    ],
});

```

## Basics

### Configuration

#### Api Reference

| key  | value |
| ------------- | ------------- |
| **path**  | `resource_path('js/Pages')`  |
| **extension**  | vue  |

The specific configuration for **Inertia Volt** can be published via:
```
php artisan vendor:publish --tag=inertia-volt
```
This will copy the default configuration to your application folder for further customization.

To change the file extension, you can set the `INERTIA_VOLT_EXTENSION` environment variable:
```
// .env

INERTIA_VOLT_EXTENSION=tsx
```

### Page Components
A component is considered a Page component if:

1. It is located under the configured path (default: resources/js/Pages).
2. It has the .inertia.{vue,svelte,jsx,tsx} postfix, depending on the framework used.
3. The InertiaVolt\Laravel\render function is called within the component. This acts as the main GET route to render the page.

### Usage

#### Registering a Page Component

A Page component can be registered in the web.php routes file. For example, if you have a component located at resources/js/Pages/Chirps/Index.inertia.vue, you can register it like this:

```
InertiaVolt::page('Chirps/Index');
```

Since InertiaVolt::page is essentially a Laravel route group, you can apply a prefix, name, or middleware to it:

```
InertiaVolt::page('Chirps/Index')->prefix('chirps')->name('chirps.')->middleware(['auth', 'verified']);
```

You can also wrap it in another route group:

```
Route::middleware('auth')->group(function () {
    InertiaVolt::page('Chirps/Index');
});
```

Under the hood, `InertiaVolt::page` creates a route group that encapsulates the `render` handler and other possible actions. You can think of it as a controller.

#### Page Actions

In addition to the `render` action, **Inertia Volt** provides `post`, `put`, and `delete` actions to simplify resource manipulation for a page.

These action functions return a `Route` object, meaning you can apply a prefix, name, middleware, and even model binding to them:

```
post('', function (ChirpStoreRequest $request) {
    ...
});

put('{chirp}', function (ChirpStoreRequest $request, Chirp $chirp) {
    ...
})->name('update'); // with model binding and route name
```

You can also specify an invokable class as the action handler:

```
post('', CreateChirp::class);
```