<?php

namespace Xvrmallafre\ImportProductsSwapi;

use Illuminate\Support\ServiceProvider;
use Xvrmallafre\ImportProductsSwapi\Console\Commands\ImportFromSwapi;
use Xvrmallafre\ImportProductsSwapi\Console\Commands\ExportToMagento;

class SwapiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ImportFromSwapi::class,
                ExportToMagento::class,
            ]);
        }
    }
}
