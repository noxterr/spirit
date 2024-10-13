<?php

declare(strict_types=1);

namespace Noxterr\Spirit;

use Illuminate\Support\ServiceProvider;
use Noxterr\Spirit\Console\Commands\SpiritInitializerCommand;

class SpiritServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
        $this->registerBindings();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPublishing();
        $this->registerCommands();
    }

    /**
     * Setup the configuration for Spirit.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/spirit.php', 'spirit'
        );
    }

    /**
     * Register the package's bindings in the service container.
     *
     * @return void
     */
    protected function registerBindings()
    {
        $this->app->bind('spirit', function () {
            return new Spirit;
        });
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/spirit.php' => config_path('spirit.php'),
            ], 'spirit-config');
        }
    }

    /**
     * Register the package's custom Artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            SpiritInitializerCommand::class,
        ]);
    }
}