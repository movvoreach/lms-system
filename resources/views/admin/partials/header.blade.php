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

        <!-- 📩 COMMUNICATION (CHAT QUICK ACCESS) -->
        <li class="nav-item">
            <a class="nav-link" href="#" title="សារទំនាក់ទំនង">
                <i class="fas fa-comments"></i>
            </a>
        </li>

        <!-- 🔔 NOTIFICATIONS -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">0</span>
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">ការជូនដំណឹង</span>
                <div class="dropdown-divider"></div>

                <a href="#" class="dropdown-item text-muted text-center">
                    គ្មានការជូនដំណឹងថ្មី
                </a>
            </div>
        </li>

        <!-- 🌐 LANGUAGE -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-language"></i>
                <span class="badge badge-info navbar-badge text-uppercase">
                    {{ app()->getLocale() }}
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <span class="dropdown-header">ជ្រើសរើសភាសា</span>
                <div class="dropdown-divider"></div>

                <a href="#" class="dropdown-item">
                    🇰🇭 ខ្មែរ
                </a>

                <a href="#" class="dropdown-item">
                    🇺🇸 English
                </a>
            </div>
        </li>

        <!-- 🌙 DARK MODE -->
        <li class="nav-item">
            <a class="nav-link" href="#" id="darkModeToggle">
                <i class="fas fa-moon" id="darkModeIcon"></i>
            </a>
        </li>

        <!-- 🔳 FULLSCREEN -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- 👤 USER -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">

                <img src="{{ asset('backend/dist/img/user2-160x160.jpg') }}" class="img-circle" width="30"
                    height="30" style="object-fit:cover;" alt="User">

                <span class="ml-1">
                    {{ Auth::user()->username ?? 'User' }}
                </span>

                <i class="fas fa-chevron-down text-xs ml-2"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right">

                <!-- USER INFO -->
                <div class="dropdown-item text-center bg-primary text-white">
                    <strong>{{ Auth::user()->username }}</strong><br>
                    <small>
                        {{ Auth::user()?->roles?->pluck('role_name')->join(', ') ?: 'No Roles' }}
                    </small>
                </div>

                <div class="dropdown-divider"></div>

                <!-- LOGIN / WEBSITE ACCESS -->
                <a href="/moodle-test" class="dropdown-item">
                    <i class="fas fa-sign-in-alt mr-2 text-primary"></i>
                    ចូលគេហទំព័រ
                </a>
                <!-- PROFILE -->
                <a href="{{ route('profile.show') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> ទម្រង់ Profile
                </a>

                <!-- 2FA -->
                <a href="{{ route('profile.show') }}#two-factor" class="dropdown-item">
                    <i class="fas fa-user-shield mr-2"></i> សុវត្ថិភាព 2FA
                </a>

                <!-- SETTINGS -->
                <a href="#" class="dropdown-item">
                    <i class="fas fa-cog mr-2"></i> ការកំណត់
                </a>

                <div class="dropdown-divider"></div>

                <!-- LOGOUT -->
                <a href="#" class="dropdown-item text-danger"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-power-off mr-2"></i> ចាកចេញ
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

            </div>
        </li>

    </ul>
</nav>
