<?php

use Illuminate\Database\Seeder;

class GradePermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Laravue\Models\Permission::findOrCreate('view grade', 'api');
        \App\Laravue\Models\Permission::findOrCreate('manage grade', 'api');

        // Assign new permissions to admin group
        $adminRole = App\Laravue\Models\Role::findByName(App\Laravue\Acl::ROLE_ADMIN);
        $adminRole->givePermissionTo(['view grade', 'manage grade']);
    }
}
