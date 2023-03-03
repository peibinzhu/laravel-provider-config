<?php

declare(strict_types=1);

namespace PeibinLaravel\ProviderConfig;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class ConfigServicProvider extends EventServiceProvider
{
    public function boot()
    {
        $this->app->get(DefinitionSourceFactory::class)();
        $this->app->get(ConfigFactory::class)();
    }
}
