<?php

namespace KesmenEnver\ServiceLayer;

use Illuminate\Support\ServiceProvider;

class ServiceLayerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServiceGenerator();
    }

    /**
     * Register the make:service generator.
     */
    private function registerServiceGenerator()
    {
        $this->app->singleton('command.kesmenenver.service', function ($app) {
            return $app['KesmenEnver\ServiceLayer\Commands\ServiceMakeCommand'];
        });
        $this->commands('command.kesmenenver.service');
    }
}
