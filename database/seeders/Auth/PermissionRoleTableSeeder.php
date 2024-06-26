<?php

namespace Database\Seeders\Auth;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        // Create Roles
        $super_admin = Role::firstOrCreate(['name' => 'super admin']);
        $admin = Role::firstOrCreate(['name' => 'administrator']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $executive = Role::firstOrCreate(['name' => 'executive']);
        $user = Role::firstOrCreate(['name' => 'user']);
        $student = Role::firstOrCreate(['name' => 'student']);
        $unit_admin = Role::firstOrCreate(['name' => 'unit admin']);

        // Create Permissions
        Permission::firstOrCreate(['name' => 'view_backend']);
        Permission::firstOrCreate(['name' => 'edit_settings']);
        Permission::firstOrCreate(['name' => 'student_area']);
        Permission::firstOrCreate(['name' => 'view_logs']);

        $permissions = Permission::defaultPermissions();

        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }

        \Artisan::call('auth:permission', [
            'name' => 'posts',
        ]);
        echo "\n _Posts_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'categories',
        ]);
        echo "\n _Categories_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'tags',
        ]);
        echo "\n _Tags_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'comments',
        ]);
        echo "\n _Comments_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'students',
        ]);
        echo "\n _Students_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'mkdums',
        ]);
        echo "\n _Mkdums_ Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'students',
        ]);
        echo "\n _Students Permissions Created.";

        \Artisan::call('auth:permission', [
            'name' => 'records',
        ]);
        echo "\n _Records_ Permissions Created.";

        echo "\n\n";

        // Assign Permissions to Roles
        $admin->givePermissionTo(Permission::all());
        $manager->givePermissionTo('view_backend');
        $executive->givePermissionTo('view_backend');

        $student->givePermissionTo('student_area');
        $student->givePermissionTo('view_students');
        $student->givePermissionTo('edit_students');

        $unit_admin->givePermissionTo('view_backend');
        $unit_admin->givePermissionTo('view_students');
        $unit_admin->givePermissionTo('edit_students');

        Schema::enableForeignKeyConstraints();
    }
}
