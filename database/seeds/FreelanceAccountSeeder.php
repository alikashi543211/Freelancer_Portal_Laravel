<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FreelanceAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('freelancer_accounts')->truncate();
        DB::table('freelancer_accounts')->insert([
            [
                'name' => 'Admin Account',
                'app_id' => 'caf677b4-9446-4ca5-9fd6-ee5a80604ab3',
                'app_secret' => '30410d1ab43f69aadd7d6f9977d42cba3af58dcd07fe6f0a1098ebf8cc540d739665a0e04152b432f56156e2be8d7f8d9fc9b098cf2770acfa125b5c9295b0c6',
                'access_token' => 'YSta6QXhzqcMkV5CrpKHVtO0G9pSsi',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
