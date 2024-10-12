<?php

declare(strict_types=1);

namespace Noxterr\Spirit;

use Illuminate\Support\ServiceProvider;
use Noxterr\Spirit\Console\Commands\SpiritInitializerCommand;

class SpiritServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SpiritInitializerCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/spirit.php' => config_path('spirit.php'),
        ], 'config');
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        echo "Hello from SpiritServiceProvider";
    }
}