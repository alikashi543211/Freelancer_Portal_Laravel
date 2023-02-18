<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @include('includes.notifications')
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                @if(Auth::user()->freelancer_account_id){{ Auth::user()->freelanceAccount->name }}@else Account @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                @php
                $accounts = App\FreelancerAccount::where('status',ACTIVE)->where('id','!=',Auth::user()->freelancer_account_id)->get();
                @endphp
                <ul class="navbar-nav">

                    @foreach ($accounts as $account)
                    <li class="nav-item">
                        <a class="nav-link d-block" href="{{ url('settings/accounts/'.$account->id) }}">{{ $account->name }}</a>
                    </li>
                    @endforeach
                </ul>
                <a href="{{ url('logout') }}" class="dropdown-item dropdown-footer">Logout</a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
