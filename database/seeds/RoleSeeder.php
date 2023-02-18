<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('Set FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::table('roles')->insert([
            [
                'name' => 'Admin'
            ],
            [
                'name' => 'User'
            ]
        ]);
        DB::statement('Set FOREIGN_KEY_CHECKS=1;');
    }
}
