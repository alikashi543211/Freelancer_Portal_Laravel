@extends('layout')

@section('title', $heading)

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-header text-right">
                <a href="{{ url('users/add') }}"><button class="btn btn-success btn-sm">Add New</button></a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 20px">id</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ date('M d, Y',strtotime($user->created_at)) }}</td>
                            <td>
                                <a href="{{ url('users/details/'.$user->id) }}"><button class="btn btn-xs btn-primary">Details</button></a>
                                @if($user->status == ACTIVE)
                                <a href="{{ url('users/deactivate/'.$user->id) }}"><button class="btn btn-xs btn-danger">Deactivate</button></a>
                                @else
                                <a href="{{ url('users/activate/'.$user->id) }}"><button class="btn btn-xs btn-success">Activate</button></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>

@endsection
