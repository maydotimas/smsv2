<?php

use Illuminate\Database\Seeder;

class StudentPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Laravue\Models\Permission::findOrCreate('view student', 'api');
        \App\Laravue\Models\Permission::findOrCreate('manage student', 'api');

        // Assign new permissions to admin group
        $adminRole = App\Laravue\Models\Role::findByName(App\Laravue\Acl::ROLE_ADMIN);
        $adminRole->givePermissionTo(['view student', 'manage student']);
    }
}
