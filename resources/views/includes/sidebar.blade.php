<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('leads') }}" class="brand-link">
        <img src="{{ url('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">LGP</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ url('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ url('profile/edit') }}" class="d-block">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
                @foreach (App\Permission::all() as $permission)
                @if(checkPermission($permission->id,READ))
                <li class="nav-item">
                    <a href="{{ url($permission->module) }}" class="nav-link {{ (Request::segment(1) == $permission->module && Request::segment(2) != 'my-leads') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-{{ $permission->icon }}"></i>
                        <p>
                            {{ $permission->name }}
                            @if($permission->module == 'messages')
                            <span class="badge badge-info right" id="message-count" style="display:none">0</span>
                            @endif
                        </p>
                    </a>
                </li>
                @if($permission->module == 'leads')
                <li class="nav-item">
                    <a href="{{ url('leads/my-leads') }}" class="nav-link {{ Request::segment(2) == 'my-leads' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-{{ $permission->icon }}"></i>
                        <p>
                            My Leads
                        </p>
                    </a>
                </li>
                @endif
                @endif
                @endforeach
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
