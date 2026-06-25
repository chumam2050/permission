<?php

namespace Chumam2050\Permission\Tests\Feature;

use Chumam2050\Permission\Tests\TestCase;
use Illuminate\Support\Facades\File;

class InstallCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Clean up the generated files before and after each test
        $this->cleanUpGeneratedFiles();
    }

    protected function tearDown(): void
    {
        $this->cleanUpGeneratedFiles();

        parent::tearDown();
    }

    protected function cleanUpGeneratedFiles()
    {
        $directories = [
            app_path('Models'),
            app_path('Http/Controllers'),
            app_path('Enums'),
            app_path('Policies'),
            app_path('Console/Commands'),
            app_path('Permissions'),
            app_path('Services'),
            database_path('migrations'),
        ];

        foreach ($directories as $directory) {
            if (File::exists($directory)) {
                File::deleteDirectory($directory);
            }
        }
    }

    public function test_install_command_copies_stubs_and_replaces_namespaces()
    {
        $this->artisan('permission:install')
            ->expectsOutputToContain('Installing Permission Module...')
            ->expectsOutputToContain('Permission module scaffolding installed successfully.')
            ->assertExitCode(0);

        // Verify some expected files exist
        $this->assertTrue(File::exists(app_path('Models/Group.php')));
        $this->assertTrue(File::exists(app_path('Http/Controllers/PermissionController.php')));
        $this->assertTrue(File::exists(app_path('Services/PermissionMenuService.php')));
        
        // Verify the namespace was properly replaced
        $groupModelContent = File::get(app_path('Models/Group.php'));
        $this->assertStringContainsString('namespace App\\Models;', $groupModelContent);
        
        $controllerContent = File::get(app_path('Http/Controllers/PermissionController.php'));
        $this->assertStringContainsString('namespace App\\Http\\Controllers;', $controllerContent);
        $this->assertStringContainsString('use App\\Models\\Permission;', $controllerContent);
    }
    
    public function test_install_command_does_not_overwrite_by_default()
    {
        // Manually create a file to simulate an existing file
        File::makeDirectory(app_path('Models'), 0755, true, true);
        File::put(app_path('Models/Group.php'), '<?php // Original Content');

        $this->artisan('permission:install')
            ->expectsOutputToContain('File already exists: ' . app_path('Models/Group.php') . '. Use --force to overwrite.')
            ->assertExitCode(0);

        // Content should still be the original
        $this->assertEquals('<?php // Original Content', File::get(app_path('Models/Group.php')));
    }

    public function test_install_command_overwrites_with_force()
    {
        // Manually create a file to simulate an existing file
        File::makeDirectory(app_path('Models'), 0755, true, true);
        File::put(app_path('Models/Group.php'), '<?php // Original Content');

        $this->artisan('permission:install', ['--force' => true])
            ->assertExitCode(0);

        // Content should be overwritten by the stub
        $content = File::get(app_path('Models/Group.php'));
        $this->assertStringContainsString('namespace App\\Models;', $content);
        $this->assertStringNotContainsString('Original Content', $content);
    }
}
