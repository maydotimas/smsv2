<?php

use Illuminate\Database\Seeder;

class DepartmentPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Laravue\Models\Permission::findOrCreate('view department', 'api');
        \App\Laravue\Models\Permission::findOrCreate('manage department', 'api');

        // Assign new permissions to admin group
        $adminRole = App\Laravue\Models\Role::findByName(App\Laravue\Acl::ROLE_ADMIN);
        $adminRole->givePermissionTo(['view department', 'manage department']);
    }
}
