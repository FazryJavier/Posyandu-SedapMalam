<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Roles for the admin panel (RBAC).
 *
 * - super_admin    : bypasses every check (Filament Shield).
 * - admin          : manages resident data, sessions, cadres and the CMS, but not roles.
 * - cadre          : records residents, households and monthly measurements.
 * - content_editor : edits only the public website content.
 *
 * Permissions follow Shield's "Action:Entity" naming (e.g. "Update:Resident").
 */
class RolesAndPermissionsSeeder extends Seeder
{
    private const ALL_ACTIONS = [
        'ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny',
        'Restore', 'RestoreAny', 'ForceDelete', 'ForceDeleteAny', 'Reorder', 'Replicate',
    ];

    private const READ = ['ViewAny', 'View'];

    private const WRITE = ['ViewAny', 'View', 'Create', 'Update'];

    private const MANAGE = ['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny', 'Restore', 'RestoreAny', 'Reorder', 'Replicate'];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Create one permission per resource/page (policies are committed in app/Policies).
        Artisan::call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
            '--option' => 'permissions',
            '--no-interaction' => true,
        ]);

        Role::findOrCreate('super_admin', 'web');

        $matrix = [
            'admin' => [
                'Neighborhood' => self::MANAGE,
                'Household' => self::MANAGE,
                'Resident' => self::MANAGE,
                'PosyanduSession' => self::MANAGE,
                'Cadre' => self::MANAGE,
                'Program' => self::MANAGE,
                'Faq' => self::MANAGE,
                'GalleryItem' => self::MANAGE,
                'User' => self::WRITE,
                'ManageSiteSettings' => ['View'],
            ],
            'cadre' => [
                'Neighborhood' => self::READ,
                'Household' => self::WRITE,
                'Resident' => self::WRITE,
                'PosyanduSession' => self::WRITE,
                'Cadre' => self::READ,
            ],
            'content_editor' => [
                'Program' => self::MANAGE,
                'Faq' => self::MANAGE,
                'GalleryItem' => self::MANAGE,
                'Cadre' => self::READ,
                'ManageSiteSettings' => ['View'],
            ],
        ];

        foreach ($matrix as $roleName => $entities) {
            $names = [];
            foreach ($entities as $entity => $actions) {
                foreach ($actions as $action) {
                    $names[] = "{$action}:{$entity}";
                }
            }

            $permissions = Permission::query()->whereIn('name', $names)->where('guard_name', 'web')->get();

            Role::findOrCreate($roleName, 'web')->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
