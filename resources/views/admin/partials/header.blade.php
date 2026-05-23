<nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">

    <!-- LEFT NAVBAR -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- RIGHT NAVBAR -->
    <ul class="navbar-nav ml-auto">

        <!-- FULLSCREEN -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- DARK MODE -->
        <li class="nav-item">
            <a class="nav-link" href="#" id="darkModeToggle">
                <i class="fas fa-moon" id="darkModeIcon"></i>
            </a>
        </li>


        <!-- LANGUAGE DROPDOWN -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-language"></i>
                <span class="badge badge-info navbar-badge text-uppercase">

                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <span class="dropdown-header">

                </span>

            </div>

        </li>

        <!-- USER DROPDOWN -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <img src="{{ asset('backend/dist/img/user2-160x160.jpg') }}" class="img-circle" width="30" height="30"
                    style="object-fit:cover;" alt="User avatar">

                <span class="ml-1">
                    {{ Auth::user()->username ?? 'User' }}
                </span>

                <i class="fas fa-chevron-down text-xs ml-2"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right">

                <div class="dropdown-item text-center bg-primary text-white">
                    <strong>Users</strong><br>
                    <small>
                        {{ Auth::user()?->roles?->pluck('role_name')->join(', ') ?: 'No Roles' }}
                    </small>
                </div>

                <div class="dropdown-divider"></div>

                <a href="{{ route('profile.show') }}#two-factor" class="dropdown-item">
                    <i class="fas fa-user-shield mr-2"></i> 2FA
                </a>
                <a href="{{ route('profile.show') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>

                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item text-danger"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-power-off mr-2"></i>
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

            </div>
        </li>

    </ul>
</nav>
