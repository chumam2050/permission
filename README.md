# Permission Module Package

A publishable Laravel module scaffold for dynamic Permissions and Group Permissions management. This package provides all the foundational models, migrations, controllers, services, policies, and resources to build a role/permission system into your Laravel application.

## Features

- **Models & Migrations:** Out-of-the-box `Group`, `Permission`, `GroupPermission`, and `GroupMember` models.
- **Controllers:** Ready-to-use API endpoints for assigning permissions, retrieving menus, and managing groups.
- **Services:** `PermissionMenuService` for dynamically constructing feature/module based navigation.
- **Enums & Traits:** Scoped enums, query scopes (`CommonScope`), and customizable pagination.
- **Artisan Commands:** A custom command `php artisan make:permission` to scaffold enum constants for specific permissions.

## Installation

You can install the package locally by linking it in your `composer.json` or by adding it directly if published to Packagist.

### Local Installation

If this package is in a local directory (e.g., `packages/choerulumam/permission`), add it to your main `composer.json`:

```json
"repositories": [
    {
        "type": "path",
        "url": "packages/choerulumam/permission"
    }
],
"require": {
    "choerulumam/permission": "*"
}
```

Then run:

```bash
composer require choerulumam/permission
```

## Setup & Scaffolding

Since this package acts as a scaffold, you **must publish** its resources into your host Laravel application. This copies the Models, Controllers, Policies, Services, and Migrations directly into your `app/` and `database/` directories so you can customize them freely.

Run the installation command:

```bash
php artisan permission:install
```

If you need to overwrite existing files, you can append `--force`:

```bash
php artisan permission:install --force
```

After scaffolding, run your migrations:

```bash
php artisan migrate
```

## Usage

### 1. Generating Enums
You can easily create new Enums using the published command:

```bash
php artisan make:permission ModuleName FeatureName
```

This will automatically create a permission enum inside `app/Permissions`.

### 2. Available Endpoints
The scaffold publishes controllers that handle common API structures. For example, `GroupPermissionController` handles the following operations:
- Fetching permissions grouped by module.
- Fetching menus and action abilities for a specific group or authenticated user.
- Assigning and destroying permissions.

Attach these to your `routes/api.php` utilizing standard Laravel routing:

```php
use App\Http\Controllers\GroupPermissionController;

Route::get('/groups/{group}/permissions', [GroupPermissionController::class, 'index']);
Route::post('/groups/{group}/permissions', [GroupPermissionController::class, 'store']);
```

### 3. API Resources & Traits
This package publishes API resources such as `GroupResource`, `GroupPermissionResource`, and base pagination classes. They exist inside `app/Http/Resources/` and use the default `App\` namespace. You can directly edit them to meet your application's requirements.

## Testing

The package provides an isolated testing environment using Orchestra Testbench. To run the internal tests and ensure all stubs and migrations are valid:

```bash
composer run test
```

## Contributing

You can modify the stubs located inside `stubs/` and update the testing environment in `tests/` to fit future iterations of your scaffolding needs.
