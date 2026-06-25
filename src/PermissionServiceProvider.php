<?php

namespace Choerulumam\Permission;

use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // No bindings required as components are published to the host application
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Choerulumam\Permission\Console\Commands\InstallPermissionModule::class,
            ]);

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'permission-migrations');
        }
    }
}
