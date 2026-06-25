<?php

namespace Chumam2050\Permission\Tests\Unit;

use Chumam2050\Permission\Tests\PublishedTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class ModelsTest extends PublishedTestCase
{
    public function test_group_can_be_created()
    {
        $groupClass = 'App\\Models\\Group';
        
        if (!class_exists($groupClass)) {
            $this->markTestSkipped('Group model not scaffolded.');
        }

        $group = $groupClass::create([
            'name' => 'Admin Group',
            'type' => 'group',
            'user_id' => 1,
        ]);

        $this->assertDatabaseHas('groups', ['name' => 'Admin Group']);
        $this->assertEquals('Admin Group', $group->name);
    }

    public function test_permission_can_be_created()
    {
        $permissionClass = 'App\\Models\\Permission';
        
        if (!class_exists($permissionClass)) {
            $this->markTestSkipped('Permission model not scaffolded.');
        }

        $permission = $permissionClass::create([
            'id' => 'auth.navigation',
            'module' => 'user',
            'feature' => 'auth',
            'action' => 'navigation',
            'description' => 'Navigation API',
        ]);

        $this->assertDatabaseHas('permissions', ['id' => 'auth.navigation']);
        $this->assertEquals('user', $permission->module);
    }

    public function test_group_permission_relation()
    {
        $groupClass = 'App\\Models\\Group';
        $permissionClass = 'App\\Models\\Permission';

        if (!class_exists($groupClass) || !class_exists($permissionClass)) {
            $this->markTestSkipped('Models not scaffolded.');
        }

        $group = $groupClass::create([
            'name' => 'Admin Group',
            'type' => 'group',
            'user_id' => 1,
        ]);

        $permission = $permissionClass::create([
            'id' => 'auth.navigation',
            'module' => 'user',
            'feature' => 'auth',
            'action' => 'navigation',
            'description' => 'Navigation API',
        ]);

        $group->permissions()->create(['permission_id' => $permission->id]);

        $this->assertDatabaseHas('group_permission', [
            'group_id' => $group->id,
            'permission_id' => $permission->id,
        ]);

        $this->assertCount(1, $group->permissions);
        $this->assertEquals($permission->id, $group->permissions->first()->permission_id);
    }
}
