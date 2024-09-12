<?php

use function InertiaVolt\Laravel\render;

render('volt', fn() => [
    'message' => 'Hello from Inertia Volt',
])->name('volt');