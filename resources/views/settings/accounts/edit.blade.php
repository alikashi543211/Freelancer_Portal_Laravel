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
            <form action="{{ url('settings/accounts/update') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $freelanceAccount->id }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" required class="form-control" value="{{ $freelanceAccount->name }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="app_id">App ID</label>
                                <input type="text" name="app_id" id="app_id" required class="form-control" value="{{ $freelanceAccount->app_id }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="app_secret">App Secret</label>
                                <input type="text" name="app_secret" id="app_secret" required class="form-control" value="{{ $freelanceAccount->app_secret }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="access_token">Access Token</label>
                                <input type="text" name="access_token" id="access_token" required class="form-control" value="{{ $freelanceAccount->access_token }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ url('settings') }}"><input type="button" class="btn btn-secondary" value="Cancel"></a>
                    <input type="submit" value="Update Account" class="btn btn-primary float-right">
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
