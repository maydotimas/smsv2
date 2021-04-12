<?php

use Illuminate\Database\Seeder;

class EnrollmentPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Laravue\Models\Permission::findOrCreate('view enrollment', 'api');
        \App\Laravue\Models\Permission::findOrCreate('manage enrollment', 'api');

        // Assign new permissions to admin group
        $adminRole = App\Laravue\Models\Role::findByName(App\Laravue\Acl::ROLE_ADMIN);
        $adminRole->givePermissionTo(['view enrollment', 'manage enrollment']);
    }
}
