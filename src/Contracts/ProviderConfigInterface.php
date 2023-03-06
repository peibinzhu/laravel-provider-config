<?php

declare(strict_types=1);

namespace PeibinLaravel\ProviderConfig\Contracts;

interface ProviderConfigInterface
{
    public function __invoke(): array;
}
