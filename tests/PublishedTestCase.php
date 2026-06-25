<?php

namespace Choerulumam\Permission\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Choerulumam\Permission\PermissionServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublishedTestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
            PermissionServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        // Define dummy classes/traits in memory if they don't exist so models can use them
        if (!class_exists('App\\Models\\User')) {
            eval("namespace App\Models; use Illuminate\Foundation\Auth\User as Authenticatable; class User extends Authenticatable {}");
        }
        if (!trait_exists('App\\Utils\\CommonScope')) {
            eval("namespace App\Utils; trait CommonScope {}");
        }
        if (!class_exists('App\\Models\\OrganizationChart')) {
            eval("namespace App\Models; use Illuminate\Database\Eloquent\Model; class OrganizationChart extends Model {}");
        }
        if (!class_exists('App\\Http\\Controllers\\Controller')) {
            eval('namespace App\Http\Controllers; abstract class Controller {
                protected function successResponse($data = null, $message = null, $code = 200) {
                    return response()->json(["data" => $data, "message" => $message], $code);
                }
                protected function failedResponse($message = null, $code = 400) {
                    return response()->json(["message" => $message], $code);
                }
            }');
        }
    }

    protected function defineDatabaseMigrations()
    {
        // Ensure scaffolding is installed before running migrations
        $this->artisan('permission:install', ['--force' => true])->run();

        // Load Laravel default migrations (e.g. users table)
        $this->loadLaravelMigrations();

        // RefreshDatabase trait will automatically run artisan migrate after this method
    }

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
    }
}
