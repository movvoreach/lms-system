<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ប្រព័ន្ធសិក្សា')</title>
    <link rel="icon" type="image/png" href="{{ asset('backend/dist/img/spilogo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;600;700;900&family=Inter:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <style>
        :root {
            --blue: #347ff0;
            --blue-dark: #245bd6;
            --ink: #142033;
            --muted: #6c7b91;
            --line: #dbe5f2;
            --soft: #f6f9fd;
            --green: #28c76f;
            --white: #fff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--ink);
            font-family: "Battambang", "Inter", Arial, sans-serif;
            background:
                linear-gradient(rgba(52, 127, 240, .055) 1px, transparent 1px),
                linear-gradient(90deg, rgba(52, 127, 240, .055) 1px, transparent 1px),
                #f8fbff;
            background-size: 72px 72px;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(248, 251, 255, .92);
            border-bottom: 1px solid rgba(219, 229, 242, .8);
            backdrop-filter: blur(16px);
        }

        .nav {
            align-items: center;
            display: grid;
            gap: 24px;
            grid-template-columns: 240px 1fr auto;
            margin: 0 auto;
            max-width: 1540px;
            padding: 12px 28px;
        }

        .brand {
            align-items: center;
            display: flex;
            gap: 12px;
            min-width: 0;
        }

        .brand img {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
            height: 48px;
            object-fit: contain;
            padding: 5px;
            width: 48px;
        }

        .brand strong {
            display: block;
            font-size: 18px;
            font-weight: 900;
            line-height: 1.15;
        }

        .brand span {
            color: var(--muted);
            display: block;
            font-size: 13px;
            line-height: 1.25;
        }

        .top-search {
            align-items: center;
            display: flex;
            gap: 12px;
            min-width: 0;
        }

        .search-box {
            align-items: center;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
            display: flex;
            gap: 10px;
            min-height: 50px;
            padding: 0 16px;
            width: min(480px, 100%);
        }

        .search-box input {
            border: 0;
            color: var(--ink);
            font: inherit;
            outline: 0;
            width: 100%;
        }

        .chip-row {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 2px;
        }

        .chip {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 999px;
            color: #3d4b60;
            cursor: pointer;
            display: inline-flex;
            font-weight: 800;
            min-height: 46px;
            padding: 10px 22px;
            white-space: nowrap;
        }

        .chip.active {
            background: var(--blue);
            border-color: var(--blue);
            color: #fff;
        }

        .nav-actions {
            align-items: center;
            display: flex;
            gap: 10px;
        }

        .login-link,
        .admin-link {
            border: 1px solid var(--line);
            border-radius: 12px;
            color: #344054;
            font-weight: 800;
            padding: 10px 16px;
            white-space: nowrap;
        }

        .admin-link {
            background: var(--blue);
            border-color: var(--blue);
            color: #fff;
        }

        .page {
            margin: 0 auto;
            max-width: 1540px;
            padding: 38px 28px 64px;
        }

        .page-title {
            margin-bottom: 28px;
        }

        .page-title h1 {
            font-size: 34px;
            font-weight: 900;
            line-height: 1.3;
            margin: 0;
        }

        .page-title p {
            color: var(--muted);
            font-size: 17px;
            margin: 8px 0 0;
            max-width: 760px;
        }

        .course-grid {
            display: grid;
            gap: 28px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .course-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 10px;
            box-shadow: 0 18px 40px rgba(35, 59, 92, .08);
            display: flex;
            flex-direction: column;
            min-height: 560px;
            overflow: hidden;
        }

        .course-art {
            align-items: center;
            color: #fff;
            display: flex;
            height: 250px;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .course-art::before {
            background:
                linear-gradient(135deg, rgba(255, 255, 255, .18), transparent 36%),
                repeating-linear-gradient(90deg, rgba(255, 255, 255, .14) 0 1px, transparent 1px 64px);
            content: "";
            inset: 0;
            position: absolute;
        }

        .course-art.python {
            background: linear-gradient(145deg, #346d9f 0 52%, #ffe66d 53% 100%);
        }

        .course-art.git {
            background: #f05234;
        }

        .course-art.php {
            background: #5867a8;
        }

        .course-art.web {
            background: linear-gradient(135deg, #ff6b35 0 50%, #2774f0 50% 100%);
        }

        .course-art.java {
            background: #df4b32;
        }

        .course-art.default {
            background: #65b7df;
        }

        .course-art .logo-mark {
            align-items: center;
            display: flex;
            gap: 18px;
            position: relative;
            text-shadow: 0 5px 10px rgba(0, 0, 0, .24);
        }

        .course-art i {
            font-size: 82px;
        }

        .course-art b {
            font-family: "Inter", Arial, sans-serif;
            font-size: 58px;
            font-weight: 800;
            letter-spacing: 0;
            line-height: 1;
        }

        .course-body {
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 30px;
        }

        .badge-soft {
            align-self: flex-start;
            background: #dffbea;
            border-radius: 999px;
            color: #149251;
            font-size: 14px;
            font-weight: 900;
            padding: 5px 14px;
        }

        .course-body h2 {
            font-size: 25px;
            font-weight: 900;
            line-height: 1.3;
            margin: 18px 0 8px;
        }

        .course-body p {
            color: #66758d;
            font-size: 17px;
            line-height: 1.8;
            margin: 0;
        }

        .course-meta {
            color: var(--muted);
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
        }

        .course-meta span {
            align-items: center;
            display: inline-flex;
            gap: 7px;
        }

        .course-button {
            align-items: center;
            background: var(--blue);
            border-radius: 8px;
            color: #fff;
            display: flex;
            font-size: 18px;
            font-weight: 900;
            justify-content: center;
            margin-top: auto;
            min-height: 58px;
        }

        .course-shell {
            display: grid;
            gap: 28px;
            grid-template-columns: 420px minmax(0, 1fr);
        }

        .module-sidebar {
            align-self: start;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 10px;
            max-height: calc(100vh - 118px);
            overflow: auto;
            padding: 18px;
            position: sticky;
            top: 94px;
        }

        .module-title {
            align-items: center;
            background: #2548bd;
            border-radius: 8px;
            color: #fff;
            display: flex;
            gap: 14px;
            font-weight: 900;
            margin: 0 0 12px;
            padding: 14px 16px;
        }

        .module-number {
            align-items: center;
            background: rgba(255, 255, 255, .9);
            border-radius: 999px;
            color: #2548bd;
            display: inline-flex;
            flex: 0 0 30px;
            height: 30px;
            justify-content: center;
            width: 30px;
        }

        .lesson-list {
            list-style: none;
            margin: 0 0 22px;
            padding: 0;
        }

        .lesson-list a {
            align-items: center;
            border-radius: 8px;
            display: flex;
            gap: 12px;
            padding: 13px 14px 13px 44px;
        }

        .lesson-list a.active,
        .lesson-list a:hover {
            background: #eef4ff;
            color: var(--blue-dark);
        }

        .lesson-dot {
            background: #c8d8ff;
            border-radius: 999px;
            height: 10px;
            width: 10px;
        }

        .lesson-content {
            background: rgba(255, 255, 255, .72);
            border: 1px solid rgba(219, 229, 242, .8);
            border-radius: 10px;
            min-height: calc(100vh - 140px);
            padding: 34px 52px 58px;
        }

        .lesson-content h1 {
            color: #2b61e8;
            font-size: 38px;
            font-weight: 900;
            line-height: 1.35;
            margin: 0 0 20px;
        }

        .lesson-hero {
            align-items: center;
            background: #fff;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            margin: 0 auto 26px;
            max-width: 720px;
            min-height: 280px;
            overflow: hidden;
        }

        .lesson-hero img {
            height: 100%;
            max-height: 360px;
            object-fit: cover;
            width: 100%;
        }

        .lesson-hero .course-art {
            height: 320px;
            width: 100%;
        }

        .lesson-text,
        .lesson-text p {
            font-size: 21px;
            line-height: 1.95;
        }

        .lesson-text h2,
        .lesson-text h3 {
            color: #2b61e8;
            font-weight: 900;
            line-height: 1.45;
            margin-top: 34px;
        }

        .empty-state {
            background: #fff;
            border: 1px dashed var(--line);
            border-radius: 10px;
            color: var(--muted);
            padding: 40px;
            text-align: center;
        }

        .floating-contact {
            align-items: center;
            background: #fff;
            border: 1px solid #bcd6ff;
            border-radius: 999px;
            bottom: 22px;
            box-shadow: 0 14px 32px rgba(35, 59, 92, .14);
            color: #0783cf;
            display: flex;
            font-family: "Inter", Arial, sans-serif;
            font-weight: 800;
            gap: 10px;
            padding: 10px 18px;
            position: fixed;
            right: 24px;
            z-index: 40;
        }

        .chat-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
            bottom: 86px;
            box-shadow: 0 18px 48px rgba(35, 59, 92, .16);
            max-width: 340px;
            padding: 16px;
            position: fixed;
            right: 24px;
            width: calc(100vw - 48px);
            z-index: 45;
        }

        .chat-card h3 {
            font-size: 17px;
            font-weight: 900;
            margin: 0 0 10px;
        }

        .chat-message {
            background: #f1f6ff;
            border-radius: 12px;
            color: #40516a;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 10px;
            padding: 10px 12px;
        }

        .chat-form {
            display: grid;
            gap: 8px;
        }

        .chat-form textarea,
        .comment-form textarea {
            border: 1px solid var(--line);
            border-radius: 10px;
            font: inherit;
            min-height: 82px;
            outline: 0;
            padding: 10px 12px;
            resize: vertical;
            width: 100%;
        }

        .primary-action {
            background: var(--blue);
            border: 0;
            border-radius: 10px;
            color: #fff;
            cursor: pointer;
            font: inherit;
            font-weight: 900;
            padding: 10px 14px;
        }

        .comments {
            border-top: 1px solid var(--line);
            margin-top: 42px;
            padding-top: 28px;
        }

        .comments h2 {
            color: var(--ink);
            font-size: 24px;
            margin: 0 0 18px;
        }

        .comment {
            align-items: flex-start;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 12px;
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            padding: 14px;
        }

        .avatar-dot {
            align-items: center;
            background: #e8f0ff;
            border-radius: 999px;
            color: var(--blue-dark);
            display: inline-flex;
            flex: 0 0 38px;
            font-weight: 900;
            height: 38px;
            justify-content: center;
            width: 38px;
        }

        .comment strong {
            display: block;
            font-size: 15px;
        }

        .comment p {
            color: #526174;
            font-size: 15px;
            line-height: 1.7;
            margin: 4px 0 0;
        }

        @media (max-width: 1100px) {
            .nav {
                grid-template-columns: 1fr;
            }

            .top-search {
                flex-direction: column;
                align-items: stretch;
            }

            .course-grid,
            .course-shell {
                grid-template-columns: 1fr;
            }

            .module-sidebar {
                max-height: none;
                position: static;
            }
        }

        @media (max-width: 680px) {
            .nav,
            .page {
                padding-left: 16px;
                padding-right: 16px;
            }

            .nav-actions {
                flex-wrap: wrap;
            }

            .course-card {
                min-height: auto;
            }

            .course-art {
                height: 190px;
            }

            .course-art i {
                font-size: 58px;
            }

            .course-art b {
                font-size: 40px;
            }

            .lesson-content {
                padding: 24px 18px 42px;
            }

            .lesson-content h1 {
                font-size: 30px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <header class="site-header">
        <nav class="nav">
            <a class="brand" href="{{ route('website.home') }}">
                <img src="{{ asset('backend/dist/img/spilogo.png') }}" alt="Saint Paul Institute">
                <span>
                    <strong>SPI Learning</strong>
                    <span>ប្រព័ន្ធសិក្សាអនឡាញ</span>
                </span>
            </a>

            <div class="top-search">
                @yield('nav-search')
            </div>

            <div class="nav-actions">
                @auth
                    <a class="admin-link" href="{{ route('admin.dashboard') }}">ផ្ទាំងគ្រប់គ្រង</a>
                @else
                    <a class="login-link" href="{{ route('login') }}">ចូលប្រើ</a>
                @endauth
            </div>
        </nav>
    </header>

    @yield('content')

    <aside class="chat-card" id="chatCard" hidden>
        <h3>ជំនួយការសិក្សា</h3>
        <div class="chat-message">សួស្តី! បើអ្នកមានសំណួរអំពីមេរៀន សូមផ្ញើសារ មតិយោបល់ ឬបញ្ហាដែលអ្នកជួប។</div>
        <form class="chat-form">
            <textarea placeholder="សរសេរសំណួរ ឬមតិយោបល់..."></textarea>
            <button class="primary-action" type="button">ផ្ញើសារ</button>
        </form>
    </aside>

    <button class="floating-contact" id="chatToggle" type="button">
        <i class="fab fa-telegram"></i>
        @CodeKhmerLearning
    </button>

    @stack('scripts')
    <script>
        const chatToggle = document.getElementById('chatToggle');
        const chatCard = document.getElementById('chatCard');

        chatToggle?.addEventListener('click', () => {
            chatCard.hidden = !chatCard.hidden;
        });
    </script>
</body>

</html>
