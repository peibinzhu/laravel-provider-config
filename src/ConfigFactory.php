<?php

declare(strict_types=1);

namespace PeibinLaravel\ProviderConfig;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use PeibinLaravel\ProviderConfig\ConfigFactory\CommandFactory;
use PeibinLaravel\ProviderConfig\ConfigFactory\ListenerProviderFactory;
use PeibinLaravel\ProviderConfig\ConfigFactory\VendorPublishFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ConfigFactory
{
    protected Repository $config;

    /**
     * @param Container $container
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(protected Container $container)
    {
        $this->config = $container->get(Repository::class);
    }

    public function __invoke(): void
    {
        $config = $this->config->all();
        $items = array_merge_recursive(ProviderConfig::load(), $config);

        $this->config->set($items);
        $this->container->get(CommandFactory::class)();
        $this->container->get(VendorPublishFactory::class)();
    }
}
