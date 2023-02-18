@extends('layout')

@section('title',$heading)

@section('content')

<div class="row">
    <div class="col-md-12">
        <form action="{{ url('profile/update') }}" method="post">
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
                                <input type="text" required class="form-control" id="first-name" placeholder="First Name" name="first_name" value="{{ Auth::user()->first_name }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last-name">Last Name</label>
                                <input type="text" required class="form-control" id="last-name" placeholder="Last Name" name="last_name" value="{{ Auth::user()->last_name }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" required class="form-control" id="email" placeholder="Email" name="email" value="{{ Auth::user()->email }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <input type="submit" class="btn btn-success" value="Update">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </div>
    <!--/.col (left) -->
</div>
<div class="row">
    <div class="col-md-12">
        <form action="{{ url('profile/update-password') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-header">
                    Change Password
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" required class="form-control" id="password" placeholder="New Password" name="password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Confirm Password</label>
                                <input type="password" required class="form-control" id="password" placeholder="Confirm Password" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <input type="submit" class="btn btn-success" value="Update Password">
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
