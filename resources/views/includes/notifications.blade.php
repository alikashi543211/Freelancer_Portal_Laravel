@php
$notifications = \App\Notification::where('user_id',Auth::id())->orderBy('created_at','desc')->where('read',false)->take(5)->get();
@endphp
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">{{ count($notifications) }}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">
            @if(count($notifications) > 0)
            {{ count($notifications) }} Notifications
            <div class="dropdown-divider"></div>
            @else
            No New notification
            @endif
        </span>
        @if(count($notifications)>0)
        @foreach ($notifications as $notification)
        <a href="@if($notification->type == NOTIFICATION_MESSAGE){{ url('messages?id='.$notification->thread_id) }}@else{{ url('leads/'.$notification->project_id.'/details') }}@endif" class="dropdown-item" style="float: left;margin-top: 5px;">
            @if($notification->type == NOTIFICATION_MESSAGE)
            <i class=" fas fa-envelope-open mr-2" style="margin-top:5px;float: left;"></i>
            @else <i class=" fas fa-th mr-2" style="margin-top:5px;float: left;"></i>
            @endif
            <span style="padding-left: 25px;display: block;white-space:normal;font-size:0.8rem;">{{ $notification->message }} <span class="float-right text-muted text-sm">{{ timeAgo($notification->created_at) }}</span></span>
        </a>
        @if(!$loop->last)<div class="dropdown-divider"></div>@endif
        @endforeach
        @endif
    </div>
</li>
