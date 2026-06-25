<?php

namespace Choerulumam\Permission\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Choerulumam\Permission\PermissionServiceProvider;

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
