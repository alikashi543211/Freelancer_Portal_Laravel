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
            <form action="{{ url('settings/accounts/store') }}" method="post">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" required class="form-control" value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="app_id">App ID</label>
                                <input type="text" name="app_id" id="app_id" required class="form-control" value="{{ old('app_id') }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="app_secret">App Secret</label>
                                <input type="text" name="app_secret" id="app_secret" required class="form-control" value="{{ old('app_secret') }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="access_token">Access Token</label>
                                <input type="text" name="access_token" id="access_token" required class="form-control" value="{{ old('access_token') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ url('settings') }}"><input type="button" class="btn btn-secondary" value="Cancel"></a>
                    <input type="submit" value="Add Account" class="btn btn-primary float-right">
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
