<?php

declare(strict_types=1);

namespace InertiaVolt\Laravel;

use Exception;

class PageContext
{
    private ?string $component = null;

    private bool $renderTriggered = false;

    public function setComponent(string $component): self
    {
        $this->component = $component;

        return $this;
    }

    public function component(): string
    {
        if (! $this->component) {
            throw new Exception('Component not set');
        }

        $this->renderTriggered = true;

        return $this->component;
    }

    public function renderTriggered(): bool
    {
        return $this->renderTriggered;
    }

    public function flush(): void
    {
        $this->component = null;
        $this->renderTriggered = false;
    }
}
