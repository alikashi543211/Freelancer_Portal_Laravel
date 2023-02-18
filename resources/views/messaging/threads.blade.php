 @if($response->status == 'success')
 @php
 $users= $response->result->users;
 $threads = $response->result->threads;
 @endphp
 <input type="hidden" name="thread_id" id="thread_id">
 @foreach ($threads as $key => $thread)
 @php
 $user = filterUser($users,$thread->thread->members);
 @endphp
 <div class="post m-0 p-3 border border-left-0 border-right-0 border-top-0 @if($loop->last) border-bottom-0 @endif @if($thread->message_unread_count > 0) bg-dark text-light @endif" data-thread_id="{{ $thread->id }}" data-project_id="{{ $thread->thread->context->id }}" data-user="{{ json_encode($user) }}">
     <div class="row">
         <div class="col-sm-2">
             <img src="http:{{ $user->avatar_cdn }}" alt="Profile Pic" class="img-fluid img-circle img-bordered-sm">
         </div>
         <div class="col-sm-8">
             {{-- @if($thread->message_unread_count > 0) --}}
             {{-- {{ dd($thread,$key,$user) }} --}}
             {{-- @endif --}}
             <h6>{{ $user->public_name ? $user->public_name : $user->display_name }}</h6>
             <p class="small">{{ substr($thread->thread->message->message,0,100) }}@if(strlen($thread->thread->message->message)>100)...@endif</p>
         </div>
         <div class="col-sm-2 d-flex justify-content-center align-items-center">
             @if($thread->message_unread_count > 0)<span class="d-flex bg-primary text-white rounded-circle justify-content-center align-items-center unread-count" style="width: 30px;height: 30px;">{{ $thread->message_unread_count }}</span>@endif
         </div>
     </div>
 </div>
 @endforeach
 @endif
