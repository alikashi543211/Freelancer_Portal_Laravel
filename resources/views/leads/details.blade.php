@extends('layout')

@section('title', $project->title)
@section('head')
@parent
<style>
    .tab-details {
        padding-bottom: 10px !important;
    }

    .active-tab {
        border-bottom: 5px solid #343a40;
        padding-bottom: 10px !important;
    }

</style>
@endsection
@section('content')
@php
$owner = $project->owner;
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h5>{{ $project->title }}</h5>
                    </div>
                    <div class="col-md-2 text-right">
                        <h5 class="d-inline mr-3 bg-secondary h6 p-1 ">{{  ucfirst(implode(' ',explode('_', $project->frontend_project_status))) }}</h5>
                        @php
                        $userLead = \App\UserLead::where('lead_id',$project->id)->first();
                        @endphp
                        @if (!empty($userLead) && $userLead->watch == ACTIVE)
                        <a href="{{ url('leads/update-watch-status/'.$project->id) }}"><button class="btn btn-primary">Unwatch Project</button></a>
                        @else
                        <a href="{{ url('leads/update-watch-status/'.$project->id) }}"><button class="btn btn-primary">Watch Project</button></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ url('leads/'.$project->id.'/details') }}" class="p-3 @if(Request::segment(3) == 'details') active-tab @else tab-details @endif">Details</a>
                <a href="{{ url('leads/'.$project->id.'/proposals') }}" class="p-3 @if(Request::segment(3) == 'proposals') active-tab @else tab-details @endif">Proposals</a>
            </div>
        </div>
    </div>
</div>
@if(Request::segment(3) == 'details')

{{-- {{ dd($project) }} --}}
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Project Details</h4>
                    </div>
                    <div class="col-md-4 text-right">
                        <h5>{{ $project->currency->sign.$project->budget->minimum }} @if(!empty($project->budget->maximum))- {{ $project->budget->maximum }} @endif {{ $project->currency->code }}</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="" style="white-space: break-spaces">{{ $project->description }}</p>
                <br>
                <h5 class="font-weight-bold">Skills Requried</h5>
                @if(!empty($project->jobs))
                @foreach ($project->jobs as $job)
                <div class="border border-dark d-inline-block px-2 py-1 small">{{ $job->name }}</div>
                @endforeach
                @endif
                <p class="small text-right"> Project ID: {{ $project->id }}</p>
                @if(!empty($project->attachments))
                <hr>
                <h5 class="font-weight-bold">Attachments</h5>
                <p><i class="fa fa-paperclip mr-3"></i>
                    @foreach ($project->attachments as $attachment)
                    <a class="mr-3" target="_blank" href="@if(strpos($attachment->url,'https://') !== false){{ $attachment->url }}@else{{ 'https://'.$attachment->url }}@endif">{{ $attachment->filename }}</a>
                    @endforeach
                </p>
                @endif
            </div>

        </div>
        @if($project->frontend_project_status == 'open')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Project Details</h4>
                    </div>
                    <div class="card-body">
                        <h6 class="font-weight-bold">Bid Details</h6>
                        <form action="{{ url('leads/place-bid') }}" method="post">
                            @csrf
                            <input type="hidden" name="project_id" type="number" value="{{ $project->id }}">
                            <div class="row">
                                <div class="col-md-5">
                                    <label for="amount">Bid Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                {{ $project->currency->sign }}
                                            </span>
                                        </div>
                                        @php
                                        $minimum = !empty($project->budget->minimum) ? $project->budget->minimum : 0;
                                        $maximum = !empty($project->budget->maximum) ? $project->budget->maximum : 0;
                                        $avg = round(($minimum+$maximum)/2,1);
                                        @endphp
                                        <input type="number" class="form-control" name="amount" required id="amount" value="{{ !empty($project->bid_stats->bid_avg) ? round($project->bid_stats->bid_avg,1) : $avg }}" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text">{{ $project->currency->code }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label for="amount">This project will be delivered in</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="period" required value="7">
                                        <div class="input-group-append">
                                            <div class="input-group-text">Days</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Describe your proposal</label>
                                        <textarea class="form-control" name="description" rows="6" placeholder="What makes you the best candidate for this project?"></textarea>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Suggest a milestone</label><br>
                                        <small>Define the tasks that you will complete for this</small>
                                        <input class="form-control" name="milestone_percentage" placeholder="Milstone Percentage" required value="100">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <input type="submit" value="Place Bid" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="font-weight-bold">About the Employer</h6>
            </div>
            <div class="card-body">
                <p><i class="fas fa-map-marker-alt text-gray-dark mr-2"></i>{{ $owner->location->country->name }} @if(!empty($owner->location->country->url)) <img src="{{ $owner->location->country->flag_url }}" class="img-fluid">@endif</p>
                <p><i class="fas fa-desktop text-gray-dark mr-2"></i>{{ $owner->employer_reputation->entire_history->complete }} Projects Completed</p>
                <p><i class="fas fa-user text-gray-dark mr-2"></i><span class="bg-orange mr-2" style="color: white !important;padding:1px 3px;border-radius:2px"> {{ round($owner->employer_reputation->entire_history->overall,1) }} </span> ({{ $owner->employer_reputation->entire_history->reviews ? $owner->employer_reputation->entire_history->reviews : 0 }} Reviews)</p>
                <p><i class="fas fa-clock text-gray-dark mr-2"></i>Member since {{ date('M d, Y',$owner->registration_date) }}</p>
                <br>
                <h5 class="font-weight-bold mb-4">Employer Verification</h5>
                <p><i style="width: 20px" class="fas text-center fa-id-card mr-2 @if($owner->status->identity_verified) text-success @else text-gray-dark @endif"></i>Identity verified</p>
                <p><i style="width: 20px" class="fas text-center fa-dollar-sign mr-2 @if($owner->status->payment_verified) text-success @else text-gray-dark @endif"></i>Payment method verified</p>
                <p><i style="width: 20px" class="fas text-center fa-envelope mr-2 @if($owner->status->email_verified) text-success @else text-gray-dark @endif"></i>Email address verified</p>
                <p><i style="width: 20px" class="fas text-center fa-credit-card mr-2 @if($owner->status->deposit_made) text-success @else text-gray-dark @endif"></i>Deposit made</p>
                <p><i style="width: 20px" class="fas text-center fa-user mr-2 @if($owner->status->profile_complete) text-success @else text-gray-dark @endif"></i>Profile completed</p>
                <p><i style="width: 20px" class="fas text-center fa-phone mr-2 @if($owner->status->phone_verified) text-success @else text-gray-dark @endif"></i>Phone number verified</p>
            </div>
        </div>
    </div>
</div>
@endif

@if(Request::segment(3) == 'proposals')
<div class="row">
    <div class="col-md-9">
        @php
        $allBids= collect($bids->bids)->where('bidder_id','!=',session('freelancerUser')->id)->toArray();
        $userBids= collect($bids->bids)->where('bidder_id',session('freelancerUser')->id)->toArray();
        $allBids = collect(array_merge($userBids, $allBids))->all();
        @endphp
        @foreach ($allBids as $bid)
        @php
        $user = $bids->users->{$bid->bidder_id};
        @endphp
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1">
                                <img src="https:{{ $user->avatar_cdn }}" alt="img" class="img-fluid ">
                            </div>
                            <div class="col-md-11">
                                <h6 class="d-inline" style="font-weight: bold"><img src="http:{{ $user->location->country->highres_flag_url_cdn }}" alt="flag" class="img-fluid d-inline mr-2" width="24px"> {{ $user->public_name ? $user->public_name : $user->username }} <span class="ml-2 text-dark" style="font-weight: normal">{{ '@'.$user->username }}</span></h6><br>
                                <p class="mt-2"><span class="bg-orange mr-2" style="color: white !important;padding:1px 3px;border-radius:2px"> {{ round($user->reputation->entire_history->overall,1) }} </span> ({{ $user->reputation->entire_history->reviews ? $user->reputation->entire_history->reviews : 0 }} Reviews) <span class="ml-2">{{ round($user->reputation->entire_history->completion_rate*100,0) }}% Completion</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p @if($bid->description)style="white-space: pre-line"@endif>
                                    {{ $bid->description ?? $bid->description }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @if($user->id == session('freelancerUser')->id)
                    <div class="card-footer">
                        <div class="bid-actions text-right">
                            <a href="{{ url('leads/retract-bid/'.$bid->id.'/'.$project->id) }}">
                                <button class="btn btn-primary">Retract</button>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @if($loop->first)
        <hr> @endif
        @endforeach

    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h4>Budget</h4>
                <h5>{{ $project->currency->sign.$project->budget->minimum }} @if(!empty($project->budget->maximum))- {{ $project->budget->maximum }} @endif {{ $project->currency->code }}</h5>
                <h4 class="mt-3">Bids</h4>
                <h5>{{ $project->bid_stats->bid_count }}</h5>
                <h4 class="mt-3">Average bid</h4>
                <h5>{{ $project->currency->sign.round($project->bid_stats->bid_avg,2). ' '.$project->currency->code }}</h5>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
