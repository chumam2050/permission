<?php

namespace Chumam2050\Permission\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Chumam2050\Permission\PermissionServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            PermissionServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Add any necessary configuration here
    }
}
