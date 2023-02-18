<?php

use App\Http\Drivers\Freelancer;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::beginTransaction();
        DB::table('jobs')->truncate();
        $freelancer = new Freelancer();
        $response = $freelancer->get('projects/0.1/jobs', ['limit' => 10]);
        $jobs = $response->result;
        $allJobs = [];
        foreach ($jobs as $job) {
            $allJobs[] = [
                'id' => $job->id,
                'name' => $job->name,
                'category_id' => $job->category->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        DB::table('jobs')->insert($allJobs);
        // DB::commit();
    }
}
