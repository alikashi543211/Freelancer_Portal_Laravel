@php
$messages = array_reverse($messages->result->messages);
@endphp
@foreach ($messages as $key => $message)
@if($message->from_user == session('freelancerUser')->id)
<div class="col-md-11 offset-1 text-right" data-key="{{ $key }}">
    @if(!empty($message->message))
    <p class="message bg-primary text-white d-inline-block mb-2 py-1 px-3">{{ $message->message }}</p>
    @endif
    @if(!empty($message->attachments))
    @foreach ($message->attachments as $attachment)
    <div style="display: block">
        <div class="spinner-border text-primary spinner-border-sm" style="display: none"></div> <i class="fa fa-paperclip text-primary"></i> <a href="#" class="message bg-primary text-white d-inline-block mb-2 py-1 px-3 message-attachment" data-message_id="{{ $message->id }}" data-name="{{ $attachment->filename }}">{{ $attachment->filename }}</a>
    </div>
    @endforeach
    @endif
</div>
@else
<div class="col-md-11 d-flow-root align-items-center" data-key="{{ $key }}">
    @if(!empty($message->message))
    <p class="message bg-gray-dark d-inline-block mb-1 py-1 px-2">{{ $message->message }}</p>
    @endif
    @if(!empty($message->attachments))
    @foreach ($message->attachments as $attachment)
    <div style="display: block">
        <a href="#" class="message bg-gray-dark d-inline-block mb-1 py-1 px-2 message-attachment" data-message_id="{{ $message->id }}" data-name="{{ $attachment->filename }}">{{ $attachment->filename }}</a> <i class=" fa fa-paperclip"></i>
        <div class="spinner-border text-gray-dark spinner-border-sm" style="display: none"></div>
    </div>
    @endforeach
    @endif
</div>
@endif
@endforeach
