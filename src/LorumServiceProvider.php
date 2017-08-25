<?php

namespace Dickinsonjl\Lorum;

use Illuminate\Support\ServiceProvider;

class GroupsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        $this->app->make('Dickinsonjl\Lorum\Lorum');
    }
}