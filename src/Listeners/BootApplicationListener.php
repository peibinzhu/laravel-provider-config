<?php

declare(strict_types=1);

namespace PeibinLaravel\ProviderConfig\Listeners;

use Illuminate\Contracts\Container\Container;
use PeibinLaravel\ProviderConfig\ConfigFactory;
use PeibinLaravel\ProviderConfig\DefinitionSourceFactory;

class BootApplicationListener
{
    public function __construct(protected Container $container)
    {
    }

    public function handle(object $event): void
    {
        $this->container->get(DefinitionSourceFactory::class)();
        $this->container->get(ConfigFactory::class)();
    }
}
