<?php

namespace App\Console\Commands;

use App\Http\Drivers\Freelancer;
use App\Notification;
use App\User;
use App\UserLead;
use Illuminate\Console\Command;

class UpdateBidStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bids:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates user bids status according to freelancer';

    private $userLead, $freelancer, $notification;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->userLead = new UserLead();
        $this->freelancer = new Freelancer();
        $this->notification = new Notification();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->userLead->newQuery()->where('status', PENDING)->where('watch', ACTIVE)->chunk(100, function ($bids) {
            $bid_ids = '?';
            foreach ($bids as $key => $bid) {
                $bid_ids .= 'bids[]=' . $bid->bid_id . '&';
            }
            $count = 0;
            $empty = false;
            do {
                $data = [
                    'limit' => '100',
                    'offset' => $count * 100,
                    'project_details' => true,
                    'user_details' => true
                ];
                $response = $this->freelancer->get('projects/0.1/bids' . $bid_ids, $data);
                $count++;
                if (count($response->result->bids) == 0)
                    $empty = true;
                foreach ($response->result->bids as $key => $bid) {
                    // dd($response->result->users->{$bid->project_owner_id}->display_name);
                    if ($bid->award_status != null) {
                        $userLead = $this->userLead->newQuery()->where('bid_id', $bid->id)->first();
                        $notification = $this->notification->newInstance();
                        $notification->message = $response->result->users->{$bid->project_owner_id}->display_name . ' ' . getBidStatus($bid)['slug'] . ' your bid.';
                        $notification->user_id = $userLead->user_id;
                        $notification->project_id = $bid->project_id;
                        $notification->type = NOTIFICATION_PROJECT;
                        $notification->created_by = 1;
                        $notification->save();
                        if ($userLead->user->role_id == USER) {
                            foreach (User::where('role_id', ADMIN)->get() as $key => $user) {
                                $notification = $this->notification->newInstance();
                                $notification->message = $response->result->users->{$bid->project_owner_id}->display_name . ' ' . getBidStatus($bid)['slug'] . ' your bid.';
                                $notification->user_id = $user->id;
                                $notification->project_id = $bid->project_id;
                                $notification->type = NOTIFICATION_PROJECT;
                                $notification->created_by = 1;
                                $notification->save();
                            }
                        }
                    }
                }
            } while (!$empty);
        });
        $this->info('Bids Updated Successfully');
    }
}
