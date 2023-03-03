<?php

declare(strict_types=1);

namespace PeibinLaravel\ProviderConfig\Annotations;

use Attribute;
use PeibinLaravel\Di\Annotation\AbstractAnnotation;

#[Attribute(Attribute::TARGET_CLASS)]
class Command extends AbstractAnnotation
{
    public string $value;
}
