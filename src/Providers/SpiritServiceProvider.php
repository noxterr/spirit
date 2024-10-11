<?php

declare(strict_types=1);

namespace Noxterr\Spirit\Providers;

use Illuminate\Support\ServiceProvider;
use Noxterr\Spirit\Console\Commands\SpiritInitializerCommand;

final class SpiritServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                commands: [
                    SpiritInitializerCommand::class,
                ],
            );
        }
    }
}