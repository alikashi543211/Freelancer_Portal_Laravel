@extends('layout')

@section('title',$heading)
@section('head')
@parent
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                API Accounts <a href="{{ url('settings/accounts/add') }}" class="float-right"><button class="btn btn-primary btn-sm">Add Account</button></a>
            </div>
            <div class="card-body">
                <table class="table table-fixed table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>App ID</th>
                            <th>App Secret</th>
                            <th>Access Token</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                        <tr>
                            <td>{{ $account->name }}</td>
                            <td>{{ $account->app_id }}</td>
                            <td>*************</td>
                            <td>*************</td>
                            <td><span class="label @if($account->status == ACTIVE)text-success @else text-danger @endif">@if($account->status == ACTIVE) Active @else Deactive @endif</span></td>
                            <td>
                                @if($account->status == ACTIVE)
                                <a href="{{ url('settings/accounts/deactivate/'.$account->id) }}" class="mx-1"><i class="fa fa-eye-slash"></i></a>
                                @else
                                <a href="{{ url('settings/accounts/activate/'.$account->id) }}" class="mx-1"><i class="fa fa-eye"></i></a>
                                @endif
                                <a href="{{ url('settings/accounts/edit/'.$account->id) }}" class="mx-1"><i class="fas fa-pencil-alt"></i></a>
                                <a href="{{ url('settings/accounts/delete/'.$account->id) }}" class="mx-1"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="accordion">
            @foreach ($roles as $key => $role)
            <div class="card">
                <div class="card-header" data-toggle="collapse" href="#div{{ $key }}" style="cursor: pointer">
                    <a class="card-link">
                        {{ $role->name }}
                    </a>
                </div>
                <div id="div{{ $key }}" class="collapse @if($loop->first)show @endif" data-parent="#accordion">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">Module</div>
                                <div class="col-md-4">Read</div>
                                <div class="col-md-4">Write</div>
                            </div>
                            <hr>
                            @foreach ($permissions as $i => $permission)
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">{{ $permission->name }}</div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="{{ $role->id }}-{{ $permission->id }}-read" data-role_id="{{ $role->id }}" data-permission_id="{{ $permission->id }}" data-type="{{ READ }}" @if($permission->readPermission($role->id)) checked @endif>
                                            <label for="{{ $role->id }}-{{ $permission->id }}-read" class="custom-control-label"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="{{ $role->id }}-{{ $permission->id }}-write" data-role_id="{{ $role->id }}" data-permission_id="{{ $permission->id }}" data-type="{{ WRITE }}" @if($permission->writePermission($role->id)) checked @endif>
                                            <label for="{{ $role->id }}-{{ $permission->id }}-write" class="custom-control-label"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@section('foot')
@parent
<script src="{{ url('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        bsCustomFileInput.init();
    });

    $(document).on('change', 'input[type=checkbox]', function () {
        var t = $(this);
        $.ajax({
            url: "{{ url('settings/update') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                permission_id: t.data('permission_id'),
                role_id: t.data('role_id'),
                action_id: t.data('type')
            },
            success: function (res) {
                console.log(res);
                t.prop('checked', res.permission);
            }
        });
    });

</script>
@endsection
