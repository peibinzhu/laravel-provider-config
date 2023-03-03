<?php

declare(strict_types=1);

namespace PeibinLaravel\ProviderConfig;

use Illuminate\Contracts\Container\Container;

class DefinitionSourceFactory
{
    public function __construct(protected Container $container)
    {
    }

    public function __invoke(): void
    {
        $configFromProviders = ProviderConfig::load();

        $serverDependencies = $configFromProviders['dependencies'] ?? [];
        $dependenciesPath = config_path('dependencies.php');
        if (file_exists($dependenciesPath)) {
            $definitions = include $dependenciesPath;
            $serverDependencies = array_replace($serverDependencies, $definitions ?? []);
        }

        $warmServices = [];
        foreach ($serverDependencies as $abstract => $concrete) {
            if (
                is_string($concrete) &&
                class_exists($concrete) &&
                method_exists($concrete, '__invoke')
            ) {
                $concrete = function () use ($concrete) {
                    return $this->container->call($concrete . '@__invoke');
                };
            }

            $warmServices[] = $abstract;
            $this->container->singleton($abstract, $concrete);
        }

        foreach ($warmServices as $abstract) {
            $this->container->get($abstract);
        }
    }
}
