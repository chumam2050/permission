<?php

namespace Chumam2050\Permission\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class InstallPermissionModule extends Command
{
    protected $signature = 'permission:install {--force : Overwrite any existing files}';

    protected $description = 'Install all of the permission resources (models, controllers, etc.)';

    public function handle(Filesystem $filesystem)
    {
        $this->info('Installing Permission Module...');

        $stubsPath = __DIR__ . '/../../../stubs';
        $appNamespace = app()->getNamespace(); // e.g. "App\"
        $appNamespace = rtrim($appNamespace, '\\');

        // Copy everything from stubs to app/
        $files = $filesystem->allFiles($stubsPath);

        foreach ($files as $file) {
            $relativePath = $file->getRelativePathname();
            
            // e.g. Models/Group.php.stub -> app/Models/Group.php
            $targetPath = app_path(preg_replace('/\.stub$/', '', $relativePath));
            $targetDir = dirname($targetPath);

            if (! $filesystem->isDirectory($targetDir)) {
                $filesystem->makeDirectory($targetDir, 0755, true);
            }

            if ($filesystem->exists($targetPath) && ! $this->option('force')) {
                $this->warn("File already exists: {$targetPath}. Use --force to overwrite.");
                continue;
            }

            $content = $filesystem->get($file->getPathname());
            $content = str_replace('{{ namespace }}', $appNamespace, $content);

            $filesystem->put($targetPath, $content);
            $this->line("Copied: " . str_replace(base_path() . '/', '', $targetPath));
        }

        // Publish Migrations
        $this->callSilent('vendor:publish', ['--tag' => 'permission-migrations', '--force' => true]);
        $this->info('Published migrations.');

        $this->info('Permission module scaffolding installed successfully.');
    }
}
