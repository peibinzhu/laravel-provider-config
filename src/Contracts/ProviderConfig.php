<?php

declare(strict_types=1);

namespace PeibinLaravel\ProviderConfig\Contracts;

interface ProviderConfig
{
    public function __invoke(): array;
}
