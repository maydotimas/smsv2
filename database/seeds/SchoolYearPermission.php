<?php

use Illuminate\Database\Seeder;

class SchoolYearPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Laravue\Models\Permission::findOrCreate('view schoolyear', 'api');
        \App\Laravue\Models\Permission::findOrCreate('manage schoolyear', 'api');

        // Assign new permissions to admin group
        $adminRole = App\Laravue\Models\Role::findByName(App\Laravue\Acl::ROLE_ADMIN);
        $adminRole->givePermissionTo(['view schoolyear', 'manage schoolyear']);
    }
}
