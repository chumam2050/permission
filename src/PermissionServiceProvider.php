<?php

namespace Chumam2050\Permission;

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
                \Chumam2050\Permission\Console\Commands\InstallPermissionModule::class,
            ]);

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'permission-migrations');
        }
    }
}
