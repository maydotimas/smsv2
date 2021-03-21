<?php

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Laravue\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@laravue.dev',
            'password' => Hash::make('laravue'),
            'status' => 'VERIFIED'
        ]);
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@laravue.dev',
            'password' => Hash::make('laravue'),
            'status' => 'VERIFIED'
        ]);
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@laravue.dev',
            'password' => Hash::make('laravue'),
            'status' => 'VERIFIED'
        ]);
        $user = User::create([
            'name' => 'Parent',
            'email' => 'parent@laravue.dev',
            'password' => Hash::make('laravue'),
            'status' => 'VERIFIED'
        ]);
        $visitor = User::create([
            'name' => 'Visitor',
            'email' => 'visitor@laravue.dev',
            'password' => Hash::make('laravue'),
            'status' => 'VERIFIED'
        ]);
        /*Departments*/
        \App\Laravue\Models\Department::create([
            'name' => 'Kinder',
            'code' => 'KI',
            'description' => 'Kindergarten',
        ]);

        \App\Laravue\Models\Department::create([
            'name' => 'Primary',
            'code' => 'GR',
            'description' => 'Grade 1 to 3',
        ]);

        \App\Laravue\Models\Department::create([
            'name' => 'Intermediate',
            'code' => 'IT',
            'description' => 'Grade 4 to 6',
        ]);
        /*Grades*/
        \App\Laravue\Models\Grade::create([
            'name' => 'Kinder 1',
            'code' => 'KI1',
            'description' => 'Kinder 1',
            'order' => '1',
            'department_id' => '1',
        ]);

        \App\Laravue\Models\Grade::create([
            'name' => 'Kinder 2',
            'code' => 'KI2',
            'description' => 'Kinder 2',
            'order' => '2',
            'department_id' => '1',
        ]);

        \App\Laravue\Models\Grade::create([
            'name' => 'Grade 1',
            'code' => 'G1',
            'description' => 'Grade 1',
            'order' => '3',
            'department_id' => '2',
        ]);

        \App\Laravue\Models\Grade::create([
            'name' => 'Grade 2',
            'code' => 'G2',
            'description' => 'Grade 2',
            'order' => '4',
            'department_id' => '2',
        ]);

        \App\Laravue\Models\Grade::create([
            'name' => 'Grade 3',
            'code' => 'G3',
            'description' => 'Grade 3',
            'order' => '5',
            'department_id' => '2',
        ]);

        \App\Laravue\Models\Grade::create([
            'name' => 'Grade 4',
            'code' => 'G4',
            'description' => 'Grade 4',
            'order' => '6',
            'department_id' => '3',
        ]);

        \App\Laravue\Models\Grade::create([
            'name' => 'Grade 5',
            'code' => 'G5',
            'description' => 'Grade 5',
            'order' => '7',
            'department_id' => '3',
        ]);

        \App\Laravue\Models\Grade::create([
            'name' => 'Grade 6',
            'code' => 'G6',
            'description' => 'Grade 6',
            'order' => '8',
            'department_id' => '3',
        ]);

        /*Sections*/
        /*Kinder 1*/
        \App\Laravue\Models\Section::create([
            'name' => 'K1A',
            'code' => 'K1A',
            'description' => 'K1A',
            'order' => '1',
            'department_id' => '1',
            'grade_id' => '1',
        ]);
        \App\Laravue\Models\Section::create([
            'name' => 'K1B',
            'code' => 'K1B',
            'description' => 'K1B',
            'order' => '2',
            'department_id' => '1',
            'grade_id' => '1',
        ]);
        /*Kinder 2*/
        \App\Laravue\Models\Section::create([
            'name' => 'K2',
            'code' => 'K2',
            'description' => 'K2',
            'order' => '1',
            'department_id' => '1',
            'grade_id' => '2',
        ]);
        /*Grade 1*/
        \App\Laravue\Models\Section::create([
            'name' => 'Jobs',
            'code' => 'Jobs',
            'description' => 'Jobs',
            'order' => '1',
            'department_id' => '2',
            'grade_id' => '3',
        ]);
        \App\Laravue\Models\Section::create([
            'name' => 'Gates',
            'code' => 'Gates',
            'description' => 'Gates',
            'order' => '2',
            'department_id' => '2',
            'grade_id' => '3',
        ]);

        /*Grade 2*/
        \App\Laravue\Models\Section::create([
            'name' => 'Linux',
            'code' => 'Linux',
            'description' => 'Linux',
            'order' => '1',
            'department_id' => '2',
            'grade_id' => '4',
        ]);
        \App\Laravue\Models\Section::create([
            'name' => 'Windows',
            'code' => 'Windows',
            'description' => 'Windows',
            'order' => '2',
            'department_id' => '2',
            'grade_id' => '4',
        ]);
        /*Grade 3*/
        \App\Laravue\Models\Section::create([
            'name' => 'Fedora',
            'code' => 'Fedora',
            'description' => 'Fedora',
            'order' => '1',
            'department_id' => '2',
            'grade_id' => '5',
        ]);
        \App\Laravue\Models\Section::create([
            'name' => 'CentOs',
            'code' => 'CentOs',
            'description' => 'CentOs',
            'order' => '2',
            'department_id' => '2',
            'grade_id' => '5',
        ]);

        /*Grade 4*/
        \App\Laravue\Models\Section::create([
            'name' => 'Android',
            'code' => 'Android',
            'description' => 'Android',
            'order' => '1',
            'department_id' => '3',
            'grade_id' => '6',
        ]);
        \App\Laravue\Models\Section::create([
            'name' => 'iOS',
            'code' => 'iOS',
            'description' => 'iOS',
            'order' => '2',
            'department_id' => '3',
            'grade_id' => '6',
        ]);
        /*Grade 5*/
        \App\Laravue\Models\Section::create([
            'name' => 'Terra',
            'code' => 'Terra',
            'description' => 'Terra',
            'order' => '1',
            'department_id' => '3',
            'grade_id' => '7',
        ]);
        \App\Laravue\Models\Section::create([
            'name' => 'Giga',
            'code' => 'Giga',
            'description' => 'Giga',
            'order' => '2',
            'department_id' => '3',
            'grade_id' => '7',
        ]);
        /*Grade 6*/
        \App\Laravue\Models\Section::create([
            'name' => 'Geno',
            'code' => 'Geno',
            'description' => 'Geno',
            'order' => '1',
            'department_id' => '3',
            'grade_id' => '8',
        ]);
        \App\Laravue\Models\Section::create([
            'name' => 'Xeno',
            'code' => 'Xeno',
            'description' => 'Xeno',
            'order' => '2',
            'department_id' => '3',
            'grade_id' => '8',
        ]);

        $adminRole = Role::findByName(\App\Laravue\Acl::ROLE_ADMIN);
        $managerRole = Role::findByName(\App\Laravue\Acl::ROLE_MANAGER);
        $editorRole = Role::findByName(\App\Laravue\Acl::ROLE_EDITOR);
        $userRole = Role::findByName(\App\Laravue\Acl::ROLE_USER);
        $visitorRole = Role::findByName(\App\Laravue\Acl::ROLE_VISITOR);

        $admin->syncRoles($adminRole);
        $manager->syncRoles($managerRole);
        $editor->syncRoles($editorRole);
        $user->syncRoles($userRole);
        $visitor->syncRoles($visitorRole);
        $this->call(UsersTableSeeder::class);
        $this->call(StudentPermission::class);
        $this->call(SchoolYearPermission::class);
        $this->call(DepartmentPermission::class);
        $this->call(ReservationPermission::class);
        $this->call(GradePermission::class);
        $this->call(SectionPermission::class);

    }
}
