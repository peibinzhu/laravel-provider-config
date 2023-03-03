<?php

declare(strict_types=1);

namespace PeibinLaravel\ProviderConfig\ConfigFactory;

use Illuminate\Console\Application as Artisan;
use Illuminate\Contracts\Config\Repository;
use PeibinLaravel\Di\Annotation\AnnotationCollector;
use PeibinLaravel\ProviderConfig\Annotations\Command;

class CommandFactory
{
    public function __construct(protected Repository $config)
    {
    }

    public function __invoke(): void
    {
        $commands = $this->config->get('commands', []);

        // Append commands that defined by annotation.
        AnnotationCollector::get(Command::class);
        $annotationCommands = AnnotationCollector::getClassesByAnnotation(Command::class);
        $annotationCommands = array_keys($annotationCommands);

        $commands = array_unique(array_merge($commands, $annotationCommands));

        Artisan::starting(function ($artisan) use ($commands) {
            $artisan->resolveCommands($commands);
        });
    }
}
