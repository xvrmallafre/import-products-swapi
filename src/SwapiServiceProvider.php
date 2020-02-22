<?php

namespace Xvrmallafre\ImportProductsSwapi;

use Illuminate\Support\ServiceProvider;

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
        //TODO: declare commands
    }
}
