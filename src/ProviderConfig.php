<?php

declare(strict_types=1);

namespace PeibinLaravel\ProviderConfig;

use Illuminate\Container\Container;
use PeibinLaravel\Utils\Composer;
use PeibinLaravel\Utils\LocalComposer;

class ProviderConfig
{
    private static array $providerConfigs = [];

    /**
     * Load and merge all provider configs from components.
     * Notice that this method will cached the config result into a static property,
     * call ProviderConfig::clear() method if you want to reset the static property.
     */
    public static function load(): array
    {
        if (!static::$providerConfigs) {
            $providers = Composer::getMergedExtra('laravel')['providers'] ?? [];
            $localProviders = LocalComposer::getMergedExtra('laravel')['providers'] ?? [];
            static::$providerConfigs = static::loadProviders(array_merge($providers, $localProviders));
        }
        return static::$providerConfigs;
    }

    public static function clear(): void
    {
        static::$providerConfigs = [];
    }

    protected static function loadProviders(array $providers): array
    {
        $providerConfigs = [];
        foreach ($providers as $provider) {
            if (is_string($provider) && class_exists($provider) && method_exists($provider, '__invoke')) {
                $providerConfigs[] = (new $provider(Container::getInstance()))();
            }
        }

        return static::merge(...$providerConfigs);
    }

    protected static function merge(...$arrays): array
    {
        if (empty($arrays)) {
            return [];
        }
        $result = array_merge_recursive(...$arrays);
        if (isset($result['dependencies'])) {
            $dependencies = array_column($arrays, 'dependencies');
            $result['dependencies'] = array_merge(...$dependencies);
        }

        return $result;
    }
}
