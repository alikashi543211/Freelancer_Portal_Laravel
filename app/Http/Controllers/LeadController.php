<?php

namespace App\Http\Controllers;

use App\Http\Drivers\Freelancer;
use App\Job;
use App\Notification;
use App\User;
use App\UserLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    private $freelancer, $userLead, $job, $notification;

    public function __construct()
    {
        $this->freelancer = new Freelancer();
        $this->userLead = new UserLead();
        $this->job = new Job();
        $this->notification = new Notification();
    }
    public function index(Request $request)
    {
        $data = [
            'limit' => 20,
            'full_description' => true,
            'job_details' => true,
            'offset' => 20 * (request('page', 1) - 1)
        ];
        $response = $this->freelancer->get('projects/0.1/projects/all/', $data);
        if ($response->status == 'error') {
            return redirect('leads')->with('error', $response->message);
        }
        $total_count = $response->result->total_count;
        $projects = $this->paginate($response->result->projects, $response->result->total_count);
        $projects->withPath('leads');
        $leads = view('leads.projects', compact('projects'))->render();
        $heading = 'Leads';
        $allJobs = $this->job->newQuery()->get();
        return view('leads.index', compact('leads', 'heading', 'allJobs', 'total_count'));
    }

    public function getLeads(Request $request)
    {
        $page = 20 * (request('page', 1) - 1);
        $data = '?limit=20&full_description=true&job_details=true&offset=' . $page . '&';
        if (!empty($request->search))
            $data .= 'query=' . $request->search . '&';
        if (!empty($request->project_types)) {
            foreach ($request->project_types as $key => $type) {
                $data .= 'project_types[]=' . $type . '&';
            }
        }
        if (!empty($request->project_upgrades)) {
            foreach ($request->project_upgrades as $key => $upgrade) {
                $data .= 'project_upgrades[]=' . $upgrade . '&';
            }
        }
        if (!empty($request->jobs)) {
            foreach ($request->jobs as $key => $job) {
                $data .= 'jobs[]=' . $job . '&';
            }
        }
        $response = $this->freelancer->get('projects/0.1/projects/all' . $data, []);
        if ($response->status == 'error') {
            return [
                'leads' => '<h4 class="text-center">' . $response->message . '</h4>',
                'total' => 0,
                'response' => $response
            ];
        }
        $projects = $this->paginate($response->result->projects, $response->result->total_count);
        $projects->withPath('leads');
        $leads =  view('leads.projects', compact('projects'))->render();
        return [
            'leads' => $leads,
            'total' => $response->result->total_count,
            'response' => $response
        ];
    }

    public function myLeads()
    {
        if (Auth::user()->role_id == ADMIN) {
            $data = [
                'limit' => 20,
                'job_details' => true,
                'offset' => 20 * (request('page', 1) - 1),
                'status' => 'all',
                'role' => 'freelancer',
                'type[]' => 'projects'
            ];
            $response = $this->freelancer->get('projects/0.1/self', $data);
            if ($response->status == 'error') {
                return redirect('my-leads')->with('error', $response->message);
            }
            $projects = $this->paginate($response->result->projects->projects, $response->result->total_count);
            $projects->withPath('my-leads');
        } else {
            $data = [
                'limit' => 20,
                'job_details' => true,
                'offset' => 20 * (request('page', 1) - 1),
            ];
            $userLeads = $this->userLead->newQuery()->where('user_id', Auth::id())->get();
            $params = "projects[]=0&";
            if (count($userLeads) > 0) {
                $params = "";
                foreach ($userLeads as $key => $lead) {
                    $params .= "projects[]=" . $lead->project_id . "&";
                }
            }
            $response = $this->freelancer->get('/projects/0.1/projects?' . $params, $data);
            if ($response->status == 'error') {
                return redirect('leads/my-leads')->with('error', $response->message);
            }
            $projects = $this->paginate($response->result->projects, $response->result->total_count);
            $projects->withPath('leads/my-leads');
        }
        $heading = 'My Leads';
        $leads = view('leads.projects', compact('projects'));
        return view('leads.my-leads', compact('leads', 'heading'));
    }
    public function details($id)
    {
        $data = [
            'full_description' => true,
            'job_details' => true,
            'user_details' => true,
            'user_reputation' => true,
            'user_employer_reputation' => true,
            'user_badge_details' => true,
            'attachment_details' => true
        ];
        $response = $this->freelancer->get("projects/0.1/projects/$id/", $data);
        if ($response->status == 'error') {
            return redirect()->back()->with('error', $response->message);
        }
        $project = $response->result;
        $heading = 'Details';
        return view('leads.details', compact('project', 'heading'));
    }

    public function proposals($id)
    {
        $data = [
            'full_description' => true,
            'job_details' => true,
            'user_details' => true,
            'user_reputation' => true,
            'user_employer_reputation' => true,
            'user_badge_details' => true,
            'attachment_details' => true
        ];
        $response = $this->freelancer->get("projects/0.1/projects/$id/", $data);
        if ($response->status == 'error') {
            return redirect()->back()->with('error', $response->message);
        }
        $project = $response->result;
        $data = [
            'user_details' => true,
            'user_reputation' => true,
            'reputation' => true,
            'user_display_info' => true,
            'user_avatar' => true,
            'user_country_details' => true,
            'user_qualification_details' => true

        ];
        $response = $this->freelancer->get("projects/0.1/projects/$id/bids", $data);
        if ($response->status == 'error') {
            return redirect()->back()->with('error', $response->message);
        }
        $bids = $response->result;
        // dd($project);
        $heading = 'Proposals';
        return view('leads.details', compact('project', 'heading', 'bids'));
    }

    public function placeBid(Request $request)
    {
        $data = [
            'project_id' => (int) $request->project_id,
            'bidder_id' => session('freelancerUser')->id,
            'amount' => (float) $request->amount,
            'period' => (int) $request->period,
            'milestone_percentage' => (int) $request->milestone_percentage,
            'description' => $request->description
        ];
        $response = $this->freelancer->post("projects/0.1/bids", json_encode($data), ['Content-Type: application/json']);
        if (!empty($response) && $response->status == 'success') {
            $userLead = $this->userLead->newInstance();
            $userLead->user_id = Auth::id();
            $userLead->lead_id = $request->project_id;
            $userLead->bid_id = $response->id;
            $userLead->status = PENDING;
            $userLead->save();
            return redirect('leads/details/' . $request->project_id)->with('success', 'Bid placed successfully');
        } else return redirect()->back()->with('error', $response->message);
    }

    public function retractBid($bid_id, $project_id)
    {
        $data = [
            'action' => 'retract'
        ];
        $response = $this->freelancer->put('projects/0.1/bids/' . $bid_id, $data);
        if ($response && $response->status == 'error') {
            return redirect()->back()->with('error', $response->message);
        } else {
            $this->userLead->newQuery()->where('project_id', $project_id)->where('user_id', Auth::id())->delete();
            return redirect()->back()->with('success', 'Bid retracted successfully');
        }
    }

    public function updateWatchStatus($id)
    {
        $userLead = $this->userLead->newQuery()->where('lead_id', $id)->first();
        $userLead->watch = $userLead->watch == ACTIVE ? DEACTIVE : ACTIVE;
        $userLead->save();
        return redirect()->back();
    }

    public function checkBidStatus()
    {
        return ['success' => true, 'html' => view('includes.notifications')->render()];
    }
}
