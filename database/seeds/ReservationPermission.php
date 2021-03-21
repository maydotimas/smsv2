<?php

use Illuminate\Database\Seeder;

class ReservationPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Laravue\Models\Permission::findOrCreate('view reservation', 'api');
        \App\Laravue\Models\Permission::findOrCreate('manage reservation', 'api');
        \App\Laravue\Models\Permission::findOrCreate('parent reservation', 'api');

        // Assign new permissions to admin group
        $adminRole = App\Laravue\Models\Role::findByName(App\Laravue\Acl::ROLE_ADMIN);
        $adminRole->givePermissionTo(['view reservation', 'manage reservation', 'parent reservation']);
    }
}
