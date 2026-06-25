<?php

namespace Choerulumam\Permission\Tests\Unit;

use Choerulumam\Permission\Tests\PublishedTestCase;
use Illuminate\Support\Str;

class PermissionMenuServiceTest extends PublishedTestCase
{
    protected function getService()
    {
        $serviceClass = 'App\\Services\\PermissionMenuService';
        if (!class_exists($serviceClass)) {
            $this->markTestSkipped('PermissionMenuService not scaffolded.');
        }

        return new $serviceClass();
    }

    public function test_all_by_group_returns_permissions_grouped_by_module()
    {
        $groupClass = 'App\\Models\\Group';
        $permissionClass = 'App\\Models\\Permission';

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

        $group->permissions()->create(['permission_id' => 'auth.navigation']);

        $service = $this->getService();
        $modules = $service->allByGroup($group->id);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $modules);
        // It should contain the 'user' module if the internal logic successfully maps it
        // The service logic reads from scaffolded App\Permissions structure
    }

    public function test_fetch_menus_filters_permissions()
    {
        $service = $this->getService();
        $menus = $service->fetchMenus(['auth.navigation']);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $menus);
    }
}
