<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Creates the first super admin from environment variables so that no
 * credentials are ever stored in the repository.
 *
 * ADMIN_EMAIL, ADMIN_PASSWORD (min. 8 chars) and optionally ADMIN_NAME.
 * Nothing is created when the variables are not set.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (blank($email) || blank($password)) {
            $this->command?->warn('ADMIN_EMAIL / ADMIN_PASSWORD belum diisi: akun super admin tidak dibuat.');

            return;
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->fill([
            'name' => env('ADMIN_NAME', 'Administrator'),
            'is_active' => true,
        ]);

        // Never overwrite the password of an existing account.
        if (! $user->exists) {
            $user->password = $password;
        }

        $user->save();
        $user->assignRole(Role::findOrCreate('super_admin', 'web'));
    }
}
