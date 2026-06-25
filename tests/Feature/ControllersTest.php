<?php

namespace Choerulumam\Permission\Tests\Feature;

use Choerulumam\Permission\Tests\PublishedTestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Models\User;

class ControllersTest extends PublishedTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Dynamically define the routes since they are published to the host app
        $groupPermissionController = 'App\\Http\\Controllers\\GroupPermissionController';
        $groupController = 'App\\Http\\Controllers\\GroupController';

        if (class_exists($groupPermissionController)) {
            Route::middleware('api')->group(function () use ($groupPermissionController, $groupController) {
                Route::get('/auth/navigation', [$groupPermissionController, 'navigation']);
                Route::apiResource('groups', $groupController);
                Route::get('groups/{group}/permissions', [$groupPermissionController, 'index']);
                Route::post('groups/{group}/permissions', [$groupPermissionController, 'store']);
            });
        }
    }

    public function test_can_fetch_group_permissions()
    {
        $groupClass = 'App\\Models\\Group';
        if (!class_exists($groupClass)) {
            $this->markTestSkipped('Classes not scaffolded.');
        }

        $group = $groupClass::create([
            'name' => 'Admin Group',
            'type' => 'group',
            'user_id' => 1,
        ]);

        $response = $this->getJson("/groups/{$group->id}/permissions");

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'pagination']);
    }

    public function test_can_assign_permission_to_group()
    {
        $groupClass = 'App\\Models\\Group';
        $permissionClass = 'App\\Models\\Permission';

        if (!class_exists($groupClass)) {
            $this->markTestSkipped('Classes not scaffolded.');
        }

        $group = $groupClass::create([
            'name' => 'Admin Group',
            'type' => 'group',
            'user_id' => 1,
        ]);

        $permissionClass::create([
            'id' => 'auth.navigation',
            'module' => 'user',
            'feature' => 'auth',
            'action' => 'navigation',
            'description' => 'Navigation API',
        ]);

        $response = $this->postJson("/groups/{$group->id}/permissions", [
            'permission_id' => 'auth.navigation'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('group_permission', [
            'group_id' => $group->id,
            'permission_id' => 'auth.navigation'
        ]);
    }
}
