@extends('layout')

@section('title',$heading)

@section('content')

<div class="row">
    <div class="col-md-12">
        <form action="{{ url('users/store') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-header">
                    Basic Information
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            <div class="form-group">
                                <label for="first-name">First Name</label>
                                <input type="text" class="form-control" id="first-name" placeholder="First Name" name="first_name" @if(old('first_name')) value="{{ old('first_name') }}" @endif>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last-name">Last Name</label>
                                <input type="text" class="form-control" id="last-name" placeholder="Last Name" name="last_name" @if(old('last_name')) value="{{ old('last_name') }}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Email" name="email" @if(old('email')) value="{{ old('email') }}" @endif>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- general form elements -->
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role_id" id="role" class="custom-select">
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @if(old('role_id')==$role->id) selected @endif>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Confirm Password</label>
                                <input type="password" class="form-control" id="password" placeholder="Confirm Password" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ url('users') }}"><button type="button" class="btn btn-danger">Back</button></a>
                        </div>
                        <div class="col-md-6 text-right">
                            <input type="submit" class="btn btn-success" value="Save">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </div>
    <!--/.col (left) -->
</div>

@endsection

@section('foot')
@parent
<!-- bs-custom-file-input -->
<script src="{{ url('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
@endsection
