<?php

declare(strict_types=1);

namespace PeibinLaravel\ProviderConfig\ConfigFactory;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use PeibinLaravel\Utils\Composer;

class VendorPublishFactory
{
    public function __construct(protected Container $container)
    {
    }

    public function __invoke(): void
    {
        $providers = Composer::getMergedExtra('laravel')['providers'] ?? [];
        $providerConfigs = [];
        foreach ($providers as $provider) {
            if (is_string($provider) && class_exists($provider) && method_exists($provider, '__invoke')) {
                $providerConfigs[$provider] = (new $provider($this->container))();
            }
        }

        $publishs = [];
        foreach ($providerConfigs as $provider => $providerConfig) {
            foreach ($providerConfig['publish'] ?? [] as $publish) {
                $publishs[] = array_merge($publish, ['provider' => $provider]);
            }
        }

        foreach ($publishs as $item) {
            if (!isset($item['id'], $item['source'], $item['destination'])) {
                continue;
            }

            $id = $item['id'];
            $source = $item['source'];
            $destination = $item['destination'];
            $provider = $item['provider'];

            $paths = [$source => $destination];

            ServiceProvider::$publishes[$provider] = array_merge(
                ServiceProvider::$publishes[$provider] ?? [],
                $paths
            );

            if (!array_key_exists($id, ServiceProvider::$publishGroups)) {
                ServiceProvider::$publishGroups[$id] = [];
            }

            ServiceProvider::$publishGroups[$id] = array_merge(
                ServiceProvider::$publishGroups[$id],
                $paths
            );
        }
    }
}
