<?php

use App\Http\Drivers\Freelancer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BidsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            // DB::beginTransaction();
            DB::table('user_leads')->truncate();
            DB::table('user_leads')->delete();
            $freelance = new Freelancer();
            $data = [
                'qualification_details' => true,
                'jobs' => true,
                'avatar' => true,
                'country_details' => true,
                'profile_description' => true,
                'display_info' => true,
                'membership_details' => true,
                'balance_details' => true,
                'financial_details' => true,
                'location_details' => true,
                'portfolio_details' => true,
                'preferred_details' => true,
                'badge_details' => true,
                'status' => true,
                'employer_reputation' => true,
                'reputation_extra' => true,
                'employer_reputation_extra' => true,
                'cover_image' => true,
                'past_cover_images' => true,
                'mobile_tracking' => true,
                'deposit_methods' => true,
                'user_recommendations' => true,
                'marketing_mobile_number' => true,
                'sanction_details' => true,
                'limited_account' => true,
                'compact' => true
            ];
            $freelancerUser = $freelance->get('users/0.1/self', $data)->result;
            $bids = [];
            $count = 0;
            $empty = false;
            do {
                $data = [
                    'limit' => '5000',
                    'bidders[]' => $freelancerUser->id,
                    'offset' => $count * 5000
                ];
                $response = $freelance->get('projects/0.1/bids', $data);
                $count++;
                if (count($response->result->bids) == 0)
                    $empty = true;
                foreach ($response->result->bids as $key => $bid) {
                    $bids[] = [
                        'lead_id' => $bid->project_id,
                        'user_id' => 1,
                        'bid_id' => $bid->id,
                        'account_id' => Auth::user()->freelancer_account_id,
                        'status' => getBidStatus($bid)['status'],
                        'status_slug' => getBidStatus($bid)['slug']
                    ];
                }
            } while (!$empty);

            DB::table('user_leads')->insert($bids);
            // DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
