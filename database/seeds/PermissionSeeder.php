<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('Set FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::table('role_permissions')->truncate();
        DB::table('permissions')->insert([
            [
                'name' => 'Leads',
                'module' => 'leads',
                'code' => 'leads',
                'icon' => 'th'
            ],
            [
                'name' => 'Message',
                'module' => 'messages',
                'code' => 'messages',
                'icon' => 'envelope-open'
            ],
            [
                'name' => 'User',
                'module' => 'users',
                'code' => 'users',
                'icon' => 'users'
            ],
            [
                'name' => 'Setting',
                'module' => 'settings',
                'code' => 'settings',
                'icon' => 'cog'
            ]
        ]);
        DB::table('role_permissions')->insert([
            [
                'role_id' => ADMIN,
                'permission_id' => 1,
                'action_id' => READ
            ],
            [
                'role_id' => ADMIN,
                'permission_id' => 1,
                'action_id' => WRITE
            ],
            [
                'role_id' => ADMIN,
                'permission_id' => 2,
                'action_id' => READ
            ],
            [
                'role_id' => ADMIN,
                'permission_id' => 2,
                'action_id' => WRITE
            ],
            [
                'role_id' => ADMIN,
                'permission_id' => 3,
                'action_id' => READ
            ],
            [
                'role_id' => ADMIN,
                'permission_id' => 3,
                'action_id' => WRITE
            ],
            [
                'role_id' => ADMIN,
                'permission_id' => 4,
                'action_id' => READ
            ],
            [
                'role_id' => ADMIN,
                'permission_id' => 4,
                'action_id' => WRITE
            ],
            [
                'role_id' => USER,
                'permission_id' => 1,
                'action_id' => READ
            ],
            [
                'role_id' => USER,
                'permission_id' => 1,
                'action_id' => WRITE
            ],
            [
                'role_id' => USER,
                'permission_id' => 3,
                'action_id' => READ
            ],
            [
                'role_id' => USER,
                'permission_id' => 3,
                'action_id' => WRITE
            ]
        ]);
        DB::statement('Set FOREIGN_KEY_CHECKS=1;');
    }
}
