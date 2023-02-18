@extends('layout')

@section('title',$heading)
@section('head')
@parent
<style>
    .post {
        margin-bottom: 0px !important;
        padding: 15px !important;
        cursor: pointer;
    }

    .message-height {
        height: calc(100vh - 276px);
        overflow-y: auto;
        overflow-x: hidden;
        padding: 0 10px;
    }

    #chat-box .card-body {
        padding: 0px;
        overflow-x: hidden
    }

    #chat-box .card-body>.row {
        padding: 0px 15px;
    }

    .fixed-height {
        height: calc(100vh - 200px);
        overflow: auto;
        position: relative;
    }

    .message {
        border-radius: 10px;
    }

    .message-input {
        position: sticky;
        bottom: 0px;
    }

    .message-input>input {}

    .main-search-header {
        display: block;
        width: 100%;
        padding: 0px;
    }

</style>
<!-- Select2 -->
<link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('content')

<div class="row">
    <div class="col-lg-4 col-md-5">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-4">
                        <h4 class="mb-0">Threads</h4>
                    </div>
                    <div class="col-sm-8">
                        <div class="main-search-header">
                            <div class="in-left">
                                <div class="form-search">
                                    <input type="text" placeholder="Search" name="search" class="form-control">
                                    <i class="fa fa-search"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body fixed-height p-0" id="threads">
                {!! $threads !!}
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-7">
        <div class="card" id="chat-box" style="display: none;">
            <div class="card-header d-flex-parent align-items-center">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="mb-0 d-inline-block" id="chat-title"></h4><span id="projectStatus" class="ml-2" style="display: none"></span>
                        <img src="#" alt="Profile Pic" class="img-fluid img-sm img-circle img-bordered-sm mr-2" id="profile-pic">
                    </div>
                    <div class="col-md-6 text-right d-flex justify-content-end align-items-center">
                        @if(Auth::user()->role_id == ADMIN)
                        <h6 class="mb-0 mr-2" id="assignedUser"></h6>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#threadAssign" id="threadAssignBtn" style="display: none;">Assign Thread</button>
                        @endif
                        <a href="" target="_blank" id="project-link" style="display: none" class="ml-2"><button class="btn btn-secondary btn-sm">Project Details</button></a>

                    </div>
                </div>
            </div>
            <div class="card-body fixed-height">
                <div class="message-height d-flex justify-content-center align-items-center">
                    <div class="spinner-border text-primary" style="display: none"></div>
                    <div class="row py-2">
                    </div>
                </div>
                <div class="message-input">
                    @if(checkPermission(getPermissionId(),WRITE))
                    <form action="" method="post" enctype="multipart/form-data" id="send-message-form">
                        <input type="text" class="form-control" placeholder="Message" id="message-input">
                        <input type="hidden" name="thread_id">
                        <input type="file" multiple="true" name="files[]" class="form-control" id="files">
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@if(Auth::user()->role_id == ADMIN)
<div class="modal" id="threadAssign">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Assign Thread to User</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <form action="{{ url('messages/assign') }}" method="post" id="assignThreadForm">
                <div class="modal-body">
                    <input type="hidden" name="thread_id">
                    <div class="form-group">
                        <label for="userSelect">Select User</label>
                        <select name="user_id" id="userSelect" class="form-control select2">
                            @foreach ($usersList as $user)
                            <option value="{{ $user->id }}">{{ $user->first_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger mr-auto" data-dismiss="modal">Cancel</button>
                    <input type="submit" value="Assign" class="btn btn-primary ml-auto">
                </div>
            </form>

        </div>
    </div>
</div>
@endif

@endsection

@section('foot')
@parent
<script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    var threadId = 0;
    var projectId
    var user = null;
    messageView = true;
    var thread = null;
    $(document).on('click', '.post', function () {
        if (threadId == $(this).data('thread_id')) {
            return false;
        }
        $('#projectStatus').hide();
        $('#threadAssignBtn').hide();
        projectId = $(this).data('project_id');
        $('#project-link').hide();
        $(this).removeClass('bg-dark text-light');
        if (threadId != $(this).data('thread_id')) {
            $('.message-height>.row').html('');
        }
        $('#chat-box').find('.message-height').addClass('d-flex justify-content-center align-items-center');
        $('.spinner-border').show();
        threadId = $(this).data('thread_id');
        $('#send-message-form').find('input[name=thread_id]').val(threadId);
        $(this).find('.unread-count').remove();
        user = $(this).data('user');
        $('#profile-pic').attr('src', 'http:' + user.avatar_cdn);
        $('#chat-title').html(user.public_name ? user.public_name : user.display_name);
        $('#chat-box').show();
        $.ajax({
            url: "{{ url('messages/messages') }}",
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                thread_id: threadId,
                user: user,
                project_id: projectId
            },
            success: function (res) {
                $('#threadAssignBtn').show();
                $('#project-link').attr('href', "leads/" + projectId + "/details");
                $('#project-link').show();
                $('.spinner-border').hide();
                $('#chat-box').find('.message-height').removeClass('d-flex justify-content-center align-items-center');
                $('.message-height>.row').html('');
                $('.message-height>.row').append(res.html);
                if (res.project != null) {
                    $('#projectStatus').html('(' + res.project.status_slug + ')');
                    $('#projectStatus').show();
                }
                if (res.assignedUser) {
                    $('#assignedUser').html('Assigned to ' + res.assignedUser.user.first_name + ' ' + res.assignedUser.user.last_name);
                } else $('#assignedUser').html('');
                $(".message-height").animate({
                    scrollTop: $(".message-height")[0].scrollHeight
                }, 0);
            }
        });
    });

    $(document).on('submit', '#send-message-form', function (event) {
        event.preventDefault();
        var message = $('#message-input').val();

        var data = new FormData();
        jQuery.each(jQuery('#files')[0].files, function (i, file) {
            data.append('files[]', file);
        });
        data.append('message', message);
        data.append('thread_id', threadId);
        $.ajax({
            url: '{{ url("messages/send") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            success: function (res) {
                let html = `<div class="col-md-11 offset-1 text-right">
                    <p class="message bg-primary text-white d-inline-block mb-2 py-1 px-3">` + message + `</p>
                </div>`;
                if (message != '') {
                    $('#chat-box').find('.message-height>.row').append(html);
                }
                $('#message-input').val('');
                $('#files').val('');
                $(".message-height").animate({
                    scrollTop: $(".message-height")[0].scrollHeight
                }, 1000);
            }
        });
    });

    var responseReturned = true;
    $(document).ready(function () {

        @if(request('id'))
        $('.post').each(function () {
            if ($(this).data('thread_id') == "{{ request('id') }}") {
                $(this).trigger('click');
            }
        });
        @endif
        $('.select2').select2({
            theme: 'bootstrap4'
        })
        setInterval(function () {
            if (threadId != 0 && responseReturned) {
                responseReturned = false
                $.ajax({
                    url: "{{ url('messages/messages') }}",
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        thread_id: threadId,
                        user: user,
                        project_id: projectId
                    },
                    success: function (res) {
                        responseReturned = true
                        $('.message-height>.row').append(res.html);
                        if (res.assignedUser) {
                            $('#assignedUser').html('Assigned to ' + res.assignedUser.user.first_name + ' ' + res.assignedUser.user.last_name);
                        } else $('#assignedUser').html('');
                        // $(".message-height").animate({
                        //     scrollTop: $(".message-height")[0].scrollHeight
                        // }, 0);
                    }
                });
            }
        }, 10000)
    });

    $(document).on('click', '#threadAssignBtn', function () {
        $('#assignThreadForm').find('input[name=thread_id]').val(threadId);
    });

    $(document).on('submit', '#assignThreadForm', function (event) {
        event.preventDefault();
        $(this).find('.btn').prop('disabled', true);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                thread_id: $('#assignThreadForm').find('input[name=thread_id]').val(),
                user_id: $('#assignThreadForm').find('select[name=user_id]').val(),
                user: user
            },
            success: function (res) {
                $('#assignThreadForm').find('.btn').prop('disabled', false);
                $('#threadAssign').modal('hide');
            },
            fail: function () {
                $(this).find('.btn').prop('disabled', true);
            }
        });
    });

    $(document).on('click', '.message-attachment', function () {
        var t = $(this);
        t.siblings('.spinner-border').show();
        $.ajax({
            url: "{{ url('messages/download-attachment') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                filename: t.data('name'),
                message_id: t.data('message_id')
            },
            success: function (res) {
                t.siblings('.spinner-border').hide();
                if (res.success) {
                    window.open(res.file, '_blank');
                }
            }
        })
    });

    $(document).on('submit', 'form', function (e) {
        e.preventDefault();
    });

    $(document).on('keyup', 'input[name=search]', function () {
        var value = $(this).val().toLowerCase();
        jQuery("#threads .col-sm-8").filter(function () {
            $(this).parents('.post').toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

</script>
@endsection
