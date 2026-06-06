<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Moodle Course Demo</title>
    <link rel="icon" type="image/png" href="{{ asset('backend/dist/img/spilogo.png') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <style>
        :root {
            --moodle: #f98012;
            --moodle-dark: #b85f08;
            --ink: #1d2125;
            --muted: #5b6876;
            --line: #d7dfe7;
            --soft: #f7f8fa;
            --panel: #fff;
            --blue: #0f6cbf;
            --green: #2f8f46;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--soft);
            color: var(--ink);
            font-family: Arial, "Khmer OS Battambang", sans-serif;
            margin: 0;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font: inherit;
        }

        .login-screen {
            align-items: center;
            background:
                linear-gradient(90deg, rgba(9, 16, 27, .86), rgba(11, 94, 148, .64)),
                url("{{ asset('backend/dist/img/photo2.png') }}");
            background-position: center;
            background-size: cover;
            display: flex;
            min-height: 100vh;
            overflow: hidden;
            padding: 34px 18px;
            position: relative;
        }

        .login-screen::before {
            background:
                radial-gradient(circle at 18% 12%, rgba(255, 255, 255, .85) 0 2px, transparent 3px),
                radial-gradient(circle at 28% 70%, rgba(255, 255, 255, .72) 0 2px, transparent 3px),
                radial-gradient(circle at 62% 24%, rgba(72, 206, 255, .7) 0 3px, transparent 4px),
                radial-gradient(circle at 84% 78%, rgba(255, 255, 255, .75) 0 2px, transparent 3px);
            background-size: 180px 180px, 220px 220px, 260px 260px, 200px 200px;
            content: "";
            inset: 0;
            opacity: .8;
            position: absolute;
        }

        .login-card {
            background: rgba(234, 239, 218, .82);
            border: 1px solid rgba(255, 255, 255, .48);
            border-radius: 8px;
            box-shadow: 0 24px 70px rgba(0, 0, 0, .28);
            margin: auto;
            max-width: 626px;
            padding: 36px 38px 30px;
            position: relative;
            width: min(626px, 100%);
            z-index: 1;
        }

        .login-brand {
            align-items: center;
            display: grid;
            gap: 22px;
            grid-template-columns: 96px minmax(0, 1fr);
            margin-bottom: 22px;
        }

        .login-brand img {
            display: block;
            max-width: 96px;
            width: 100%;
        }

        .login-brand h1 {
            color: #0d6b32;
            font-size: clamp(28px, 4vw, 40px);
            font-weight: 900;
            letter-spacing: 0;
            line-height: 1.18;
            margin: 0 0 6px;
            text-shadow: 0 2px 3px rgba(255, 255, 255, .65);
        }

        .login-brand p {
            color: #173f9f;
            font-size: 20px;
            font-weight: 900;
            line-height: 1.45;
            margin: 0;
        }

        .login-alert {
            background: rgba(205, 113, 121, .34);
            border: 1px solid rgba(189, 86, 95, .48);
            border-radius: 8px;
            color: #342414;
            font-size: 20px;
            margin-bottom: 20px;
            padding: 20px;
        }

        .login-form {
            display: grid;
            gap: 20px;
        }

        .login-form input {
            background: rgba(237, 244, 249, .72);
            border: 1px solid #7a8796;
            border-radius: 8px;
            color: #1f2937;
            font-size: 20px;
            height: 52px;
            outline: none;
            padding: 0 20px;
            width: 100%;
        }

        .login-form input:focus {
            border-color: #146dba;
            box-shadow: 0 0 0 3px rgba(20, 109, 186, .18);
        }

        .login-actions {
            align-items: center;
            display: grid;
            gap: 20px;
            justify-items: center;
            margin-top: 2px;
        }

        .login-submit {
            background: #076dbb;
            border: 0;
            border-radius: 9px;
            color: #dcefff;
            cursor: pointer;
            font-size: 20px;
            min-width: 164px;
            padding: 13px 28px;
        }

        .login-submit:hover {
            background: #075f9f;
        }

        .cookie-btn {
            align-self: start;
            background: rgba(203, 213, 225, .8);
            border: 0;
            border-radius: 6px;
            color: #182333;
            cursor: pointer;
            font-size: 20px;
            padding: 11px 16px;
        }

        .login-footer {
            bottom: 20px;
            color: #fff;
            font-size: 16px;
            font-weight: 900;
            left: 50px;
            position: absolute;
            z-index: 1;
        }

        .help-bubble {
            align-items: center;
            background: rgba(219, 226, 229, .82);
            border-radius: 50%;
            bottom: 40px;
            color: #0f7b3b;
            display: inline-flex;
            font-size: 22px;
            font-weight: 900;
            height: 40px;
            justify-content: center;
            position: absolute;
            right: 40px;
            width: 40px;
            z-index: 1;
        }

        .moodle-app {
            display: none;
        }

        body.is-logged-in {
            background: var(--soft);
        }

        body.is-logged-in .login-screen {
            display: none;
        }

        body.is-logged-in .moodle-app {
            display: block;
        }

        .topnav {
            align-items: center;
            background: var(--panel);
            border-bottom: 1px solid var(--line);
            display: grid;
            gap: 18px;
            grid-template-columns: 260px minmax(0, 1fr) auto;
            min-height: 62px;
            padding: 0 18px;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .brand {
            align-items: center;
            display: flex;
            gap: 12px;
            font-size: 22px;
            font-weight: 900;
        }

        .brand-mark {
            align-items: center;
            background: var(--moodle);
            border-radius: 4px;
            color: #fff;
            display: inline-flex;
            height: 38px;
            justify-content: center;
            width: 38px;
        }

        .main-menu {
            display: flex;
            gap: 4px;
        }

        .main-menu a {
            border-radius: 4px;
            color: #374151;
            font-size: 15px;
            font-weight: 700;
            padding: 11px 14px;
        }

        .main-menu a.active,
        .main-menu a:hover {
            background: #fff3e7;
            color: var(--moodle-dark);
        }

        .top-actions {
            align-items: center;
            display: flex;
            gap: 10px;
        }

        .icon-btn {
            align-items: center;
            background: transparent;
            border: 0;
            border-radius: 4px;
            color: #4b5563;
            display: inline-flex;
            height: 38px;
            justify-content: center;
            width: 38px;
        }

        .icon-btn:hover {
            background: #edf2f7;
        }

        .avatar {
            align-items: center;
            background: #243447;
            border-radius: 50%;
            color: #fff;
            display: inline-flex;
            font-weight: 900;
            height: 38px;
            justify-content: center;
            width: 38px;
        }

        .page-shell {
            display: grid;
            grid-template-columns: 278px minmax(0, 1fr) 310px;
            min-height: calc(100vh - 62px);
        }

        .drawer,
        .blocks {
            background: var(--panel);
            border-color: var(--line);
            border-style: solid;
            position: sticky;
            top: 62px;
            height: calc(100vh - 62px);
            overflow: auto;
        }

        .drawer {
            border-width: 0 1px 0 0;
            padding: 16px;
        }

        .blocks {
            border-width: 0 0 0 1px;
            padding: 16px;
        }

        .drawer-title,
        .block-title {
            color: #374151;
            font-size: 13px;
            font-weight: 900;
            letter-spacing: .04em;
            margin: 0 0 10px;
            text-transform: uppercase;
        }

        .course-switcher {
            display: grid;
            gap: 8px;
        }

        .course-tab {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 4px;
            cursor: pointer;
            display: grid;
            gap: 4px;
            padding: 12px;
            text-align: left;
            width: 100%;
        }

        .course-tab.active {
            border-color: var(--moodle);
            box-shadow: inset 4px 0 0 var(--moodle);
        }

        .course-tab strong {
            color: var(--ink);
            font-size: 14px;
            line-height: 1.35;
        }

        .course-tab span {
            color: var(--muted);
            font-size: 12px;
        }

        .drawer-section {
            border-top: 1px solid var(--line);
            margin-top: 18px;
            padding-top: 16px;
        }

        .nav-list {
            display: grid;
            gap: 2px;
        }

        .nav-list a {
            align-items: center;
            border-radius: 4px;
            color: #394150;
            display: flex;
            gap: 10px;
            padding: 10px;
        }

        .nav-list a:hover,
        .nav-list a.active {
            background: #eef5fb;
            color: var(--blue);
        }

        .content {
            min-width: 0;
            padding: 18px 22px 38px;
        }

        .breadcrumb {
            color: var(--muted);
            display: flex;
            flex-wrap: wrap;
            font-size: 13px;
            gap: 8px;
            margin-bottom: 14px;
        }

        .course-panel {
            display: none;
        }

        .course-panel.active {
            display: block;
        }

        .course-hero {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 4px;
            overflow: hidden;
        }

        .course-cover {
            background:
                linear-gradient(90deg, rgba(249, 128, 18, .92), rgba(15, 108, 191, .82)),
                url("{{ asset('backend/dist/img/photo1.png') }}");
            background-position: center;
            background-size: cover;
            color: #fff;
            padding: 34px;
        }

        .course-cover h1 {
            font-size: clamp(28px, 4vw, 46px);
            letter-spacing: 0;
            line-height: 1.15;
            margin: 0 0 12px;
        }

        .course-cover p {
            font-size: 17px;
            line-height: 1.65;
            margin: 0;
            max-width: 780px;
        }

        .course-meta {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 14px 18px;
        }

        .badge {
            background: #eef2f7;
            border-radius: 999px;
            color: #334155;
            font-size: 13px;
            font-weight: 800;
            padding: 7px 10px;
        }

        .badge-orange {
            background: #fff3e7;
            color: var(--moodle-dark);
        }

        .section-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 4px;
            margin-top: 16px;
        }

        .section-head {
            align-items: flex-start;
            border-bottom: 1px solid var(--line);
            display: flex;
            gap: 14px;
            justify-content: space-between;
            padding: 18px;
        }

        .section-head h2 {
            font-size: 20px;
            margin: 0 0 6px;
        }

        .section-head p {
            color: var(--muted);
            line-height: 1.55;
            margin: 0;
        }

        .collapse-btn {
            background: #f3f6f9;
            border: 1px solid var(--line);
            border-radius: 4px;
            color: #374151;
            cursor: pointer;
            height: 36px;
            width: 36px;
        }

        .activity-list {
            display: grid;
        }

        .activity {
            align-items: center;
            border-bottom: 1px solid #edf1f5;
            display: grid;
            gap: 14px;
            grid-template-columns: 46px minmax(0, 1fr) auto;
            min-height: 74px;
            padding: 12px 18px;
        }

        .activity:last-child {
            border-bottom: 0;
        }

        .activity-icon {
            align-items: center;
            border-radius: 4px;
            color: #fff;
            display: inline-flex;
            height: 42px;
            justify-content: center;
            width: 42px;
        }

        .activity-icon.Page,
        .activity-icon.Lesson {
            background: var(--blue);
        }

        .activity-icon.Video,
        .activity-icon.URL {
            background: #7a4fb3;
        }

        .activity-icon.Assignment,
        .activity-icon.Quiz {
            background: var(--moodle);
        }

        .activity-icon.Forum,
        .activity-icon.File {
            background: var(--green);
        }

        .activity strong {
            display: block;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .activity span {
            color: var(--muted);
            font-size: 13px;
        }

        .status {
            border: 1px solid var(--line);
            border-radius: 999px;
            color: #475569;
            font-size: 12px;
            font-weight: 900;
            padding: 6px 10px;
            white-space: nowrap;
        }

        .status.done {
            background: #e8f6ed;
            border-color: #bde4c8;
            color: #1f7a39;
        }

        .status.submit,
        .status.required {
            background: #fff3e7;
            border-color: #ffd3a8;
            color: var(--moodle-dark);
        }

        .block {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 4px;
            margin-bottom: 14px;
            overflow: hidden;
        }

        .block-title {
            background: #f3f6f9;
            border-bottom: 1px solid var(--line);
            margin: 0;
            padding: 12px 14px;
        }

        .block-body {
            padding: 14px;
        }

        .progress-track {
            background: #e5eaf0;
            border-radius: 999px;
            height: 10px;
            overflow: hidden;
        }

        .progress-fill {
            background: var(--moodle);
            height: 100%;
        }

        .progress-text {
            color: var(--muted);
            display: flex;
            font-size: 13px;
            justify-content: space-between;
            margin-top: 8px;
        }

        .calendar {
            display: grid;
            gap: 7px;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
        }

        .calendar span {
            border-radius: 4px;
            color: #475569;
            font-size: 12px;
            padding: 7px 0;
        }

        .calendar .day-name {
            color: var(--muted);
            font-weight: 900;
        }

        .calendar .active-day {
            background: var(--moodle);
            color: #fff;
            font-weight: 900;
        }

        .upcoming {
            display: grid;
            gap: 10px;
        }

        .upcoming-item {
            border-left: 3px solid var(--moodle);
            padding-left: 10px;
        }

        .upcoming-item strong {
            display: block;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .upcoming-item span {
            color: var(--muted);
            font-size: 13px;
        }

        @media (max-width: 1100px) {
            .page-shell {
                grid-template-columns: 240px minmax(0, 1fr);
            }

            .blocks {
                display: none;
            }
        }

        @media (max-width: 760px) {
            .login-card {
                padding: 24px 18px;
            }

            .login-brand {
                grid-template-columns: 1fr;
                justify-items: center;
                text-align: center;
            }

            .login-footer {
                left: 18px;
            }

            .topnav,
            .page-shell {
                display: block;
            }

            .topnav {
                padding: 12px;
            }

            .main-menu {
                flex-wrap: wrap;
                margin-top: 12px;
            }

            .top-actions {
                margin-top: 10px;
            }

            .drawer {
                border-width: 0 0 1px;
                height: auto;
                position: static;
            }

            .content {
                padding: 14px;
            }

            .activity {
                grid-template-columns: 42px minmax(0, 1fr);
            }

            .activity .status {
                grid-column: 2;
                justify-self: start;
            }
        }
    </style>
</head>

<body>
    <section class="login-screen" aria-label="Login">
        <div class="login-card">
            <div class="login-brand">
                <img src="{{ asset('backend/dist/img/spilogo.png') }}" alt="Institute logo">
                <div>
                    <h1>សាលាកុំព្យូទ័រជាតិ</h1>
                    <p>វិទ្យាស្ថាន អប់រំ និងបណ្ដុះបណ្ដាល (LIP)</p>
                </div>
            </div>

            <div class="login-alert">Invalid login, please try again</div>

            <form class="login-form" id="demoLoginForm">
                <input type="text" name="username" placeholder="Username or email" autocomplete="username">
                <input type="password" name="password" placeholder="Password" autocomplete="current-password">
                <div class="login-actions">
                    <button class="login-submit" type="submit">Log in</button>
                    <button class="cookie-btn" type="button">Cookies notice</button>
                </div>
            </form>
        </div>

        <div class="login-footer">Leadership: CDO</div>
        <div class="help-bubble">?</div>
    </section>

    <div class="moodle-app">
        <header class="topnav">
            <a class="brand" href="{{ route('moodle.test') }}">
                <span class="brand-mark"><i class="fas fa-graduation-cap"></i></span>
                Moodle
            </a>

            <nav class="main-menu" aria-label="Main menu">
                <a class="active" href="#">Dashboard</a>
                <a href="#">My courses</a>
                <a href="#">Calendar</a>
                <a href="{{ url('/admin/dashboard') }}">Admin</a>
            </nav>

            <div class="top-actions">
                <button class="icon-btn" type="button" title="Search"><i class="fas fa-search"></i></button>
                <button class="icon-btn" type="button" title="Messages"><i class="fas fa-comment-alt"></i></button>
                <button class="icon-btn" type="button" title="Notifications"><i class="far fa-bell"></i></button>
                <span class="avatar">S</span>
            </div>
        </header>

        <div class="page-shell">
            <aside class="drawer">
            <p class="drawer-title">My courses</p>
            <div class="course-switcher">
                @foreach ($courses as $course)
                    <button class="course-tab {{ $loop->first ? 'active' : '' }}" type="button" data-course-target="course-{{ $loop->index }}">
                        <strong>{{ $course['title'] }}</strong>
                        <span>{{ $course['code'] }} - {{ $course['progress'] }}% complete</span>
                    </button>
                @endforeach
            </div>

            <div class="drawer-section">
                <p class="drawer-title">Course index</p>
                <div class="nav-list">
                    <a class="active" href="#"><i class="fas fa-home"></i> Course home</a>
                    <a href="#"><i class="fas fa-list-ul"></i> Topics</a>
                    <a href="#"><i class="fas fa-clipboard-check"></i> Grades</a>
                    <a href="#"><i class="fas fa-users"></i> Participants</a>
                    <a href="#"><i class="fas fa-cog"></i> Settings</a>
                </div>
            </div>
            </aside>

            <main class="content">
            <div class="breadcrumb">
                <span>Dashboard</span>
                <span>/</span>
                <span>My courses</span>
                <span>/</span>
                <span>Course home</span>
            </div>

            @foreach ($courses as $course)
                <article class="course-panel {{ $loop->first ? 'active' : '' }}" id="course-{{ $loop->index }}">
                    <section class="course-hero">
                        <div class="course-cover">
                            <h1>{{ $course['title'] }}</h1>
                            <p>{{ $course['description'] }}</p>
                        </div>
                        <div class="course-meta">
                            <span class="badge badge-orange">{{ $course['code'] }}</span>
                            <span class="badge">{{ $course['level'] }}</span>
                            <span class="badge">{{ $course['duration'] }}</span>
                            <span class="badge">Teacher: {{ $course['teacher'] }}</span>
                        </div>
                    </section>

                    @foreach ($course['modules'] as $module)
                        <section class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>{{ $module['title'] }}</h2>
                                    <p>{{ $module['summary'] }}</p>
                                </div>
                                <button class="collapse-btn" type="button" title="Collapse section">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </div>

                            <div class="activity-list">
                                @foreach ($module['activities'] as $activity)
                                    @php
                                        $statusClass = strtolower(str_replace(' ', '-', $activity['status']));
                                    @endphp
                                    <a class="activity" href="#">
                                        <span class="activity-icon {{ $activity['type'] }}">
                                            <i class="fas {{ $activity['icon'] }}"></i>
                                        </span>
                                        <span>
                                            <strong>{{ $activity['title'] }}</strong>
                                            <span>{{ $activity['type'] }} - {{ $activity['meta'] }}</span>
                                        </span>
                                        <span class="status {{ $statusClass }}">{{ $activity['status'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </article>
            @endforeach
            </main>

            <aside class="blocks">
            <div class="block">
                <h2 class="block-title">Course completion</h2>
                <div class="block-body" id="progressBlock">
                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ $courses->first()['progress'] }}%"></div>
                    </div>
                    <div class="progress-text">
                        <span>Progress</span>
                        <strong>{{ $courses->first()['progress'] }}%</strong>
                    </div>
                </div>
            </div>

            <div class="block">
                <h2 class="block-title">Calendar</h2>
                <div class="block-body">
                    <div class="calendar">
                        @foreach (['M', 'T', 'W', 'T', 'F', 'S', 'S'] as $day)
                            <span class="day-name">{{ $day }}</span>
                        @endforeach
                        @foreach (range(1, 28) as $day)
                            <span class="{{ in_array($day, [6, 14, 22]) ? 'active-day' : '' }}">{{ $day }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="block">
                <h2 class="block-title">Upcoming</h2>
                <div class="block-body upcoming">
                    <div class="upcoming-item">
                        <strong>Assignment due</strong>
                        <span>Today, 11:59 PM</span>
                    </div>
                    <div class="upcoming-item">
                        <strong>Weekly quiz</strong>
                        <span>Tomorrow, 9:00 AM</span>
                    </div>
                    <div class="upcoming-item">
                        <strong>Forum check</strong>
                        <span>Friday, 3:30 PM</span>
                    </div>
                </div>
            </div>
            </aside>
        </div>
    </div>

    <script>
        document.getElementById('demoLoginForm')?.addEventListener('submit', function(event) {
            event.preventDefault();
            document.body.classList.add('is-logged-in');
            window.scrollTo(0, 0);
        });

        var courseProgress = @json($courses->pluck('progress')->values());

        document.querySelectorAll('[data-course-target]').forEach(function(button, index) {
            button.addEventListener('click', function() {
                var targetId = button.getAttribute('data-course-target');

                document.querySelectorAll('[data-course-target]').forEach(function(item) {
                    item.classList.toggle('active', item === button);
                });

                document.querySelectorAll('.course-panel').forEach(function(panel) {
                    panel.classList.toggle('active', panel.id === targetId);
                });

                var progress = courseProgress[index] || 0;
                var block = document.getElementById('progressBlock');

                if (block) {
                    block.querySelector('.progress-fill').style.width = progress + '%';
                    block.querySelector('.progress-text strong').textContent = progress + '%';
                }
            });
        });

        document.querySelectorAll('.collapse-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var section = button.closest('.section-card');
                var list = section.querySelector('.activity-list');
                var icon = button.querySelector('i');
                var isHidden = list.style.display === 'none';

                list.style.display = isHidden ? 'grid' : 'none';
                icon.classList.toggle('fa-chevron-up', isHidden);
                icon.classList.toggle('fa-chevron-down', !isHidden);
            });
        });
    </script>
</body>

</html>
