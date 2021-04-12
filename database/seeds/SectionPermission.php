<?php

use Illuminate\Database\Seeder;

class SectionPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Laravue\Models\Permission::findOrCreate('view section', 'api');
        \App\Laravue\Models\Permission::findOrCreate('manage section', 'api');

        // Assign new permissions to admin group
        $adminRole = App\Laravue\Models\Role::findByName(App\Laravue\Acl::ROLE_ADMIN);
        $adminRole->givePermissionTo(['view section', 'manage section']);
    }
}
