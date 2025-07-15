<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/admin" class="brand-link">
        <span class="brand-text font-weight-light">Admin Panel</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('admin.barbers.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-cut"></i>
                        <p>Barbers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.bookings.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Bookings</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
