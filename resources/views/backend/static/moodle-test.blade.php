<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CodeKhmerVideo Landing Test</title>
    <link rel="icon" type="image/png" href="{{ asset('backend/dist/img/spilogo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700;900&family=Inter:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <style>
        :root {
            --blue: #216d9f;
            --blue-dark: #166294;
            --hero: #2486c0;
            --hero-deep: #1f76aa;
            --yellow: #ffe229;
            --green: #13e472;
            --text: #0f172a;
            --muted: #5f6f82;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--text);
            font-family: "Battambang", "Inter", Arial, sans-serif;
            background: var(--hero);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .promo {
            align-items: center;
            background: var(--blue);
            color: #fff;
            display: flex;
            font-size: 18px;
            font-weight: 900;
            gap: 12px;
            justify-content: center;
            min-height: 46px;
            padding: 6px 16px;
            text-align: center;
        }

        .dot {
            background: var(--green);
            border-radius: 999px;
            box-shadow: 0 0 14px rgba(19, 228, 114, .8);
            display: inline-block;
            height: 9px;
            width: 9px;
        }

        .nav-wrap {
            background: #f7f9fc;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .08);
        }

        .nav {
            align-items: center;
            display: grid;
            gap: 26px;
            grid-template-columns: 300px 1fr 190px;
            margin: 0 auto;
            max-width: 1520px;
            min-height: 92px;
            padding: 0 48px;
        }

        .brand {
            align-items: center;
            display: flex;
            gap: 14px;
            min-width: 0;
        }

        .brand-logo {
            align-items: center;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 24px rgba(33, 109, 159, .22);
            display: inline-flex;
            height: 52px;
            justify-content: center;
            overflow: hidden;
            width: 52px;
        }

        .brand-logo img {
            display: block;
            max-height: 42px;
            max-width: 42px;
        }

        .brand strong {
            display: block;
            font-family: "Inter", Arial, sans-serif;
            font-size: 21px;
            font-weight: 800;
            line-height: 1.1;
        }

        .brand span {
            color: var(--blue);
            display: block;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.2;
            margin-top: 3px;
        }

        .menu {
            align-items: center;
            display: flex;
            gap: 18px;
            justify-content: center;
            min-width: 0;
        }

        .menu a {
            border-radius: 8px;
            color: #475569;
            font-size: 18px;
            font-weight: 800;
            line-height: 1;
            padding: 16px 24px;
            white-space: nowrap;
        }

        .menu a.active {
            background: #e9f1f9;
            color: var(--blue);
            position: relative;
        }

        .menu a.active::after {
            background: var(--blue);
            bottom: 4px;
            content: "";
            height: 2px;
            left: 50%;
            position: absolute;
            transform: translateX(-50%);
            width: 56px;
        }

        .actions {
            align-items: center;
            display: flex;
            gap: 22px;
            justify-content: flex-end;
        }

        .login {
            color: #475569;
            font-size: 18px;
            font-weight: 800;
        }

        .cta {
            background: var(--blue);
            border-radius: 14px;
            box-shadow: 0 8px 18px rgba(33, 109, 159, .28);
            color: #fff;
            display: inline-flex;
            font-size: 18px;
            font-weight: 900;
            min-height: 50px;
            padding: 13px 26px;
        }

        .hero {
            background:
                radial-gradient(circle at 24px 24px, rgba(255, 255, 255, .055) 2px, transparent 2.5px),
                linear-gradient(135deg, var(--hero-deep), #2795cf);
            background-size: 34px 34px, auto;
            color: #fff;
            min-height: calc(100vh - 138px);
            overflow: hidden;
            position: relative;
        }

        .hero-inner {
            display: grid;
            gap: 46px;
            grid-template-columns: minmax(420px, 650px) minmax(420px, 1fr);
            margin: 0 auto;
            max-width: 1520px;
            min-height: calc(100vh - 138px);
            padding: 30px 48px 58px;
        }

        .copy {
            align-self: center;
            max-width: 690px;
            padding-top: 8px;
        }

        .pill {
            align-items: center;
            border: 1px solid rgba(255, 255, 255, .24);
            border-radius: 999px;
            display: inline-flex;
            gap: 12px;
            font-size: 18px;
            font-weight: 900;
            margin-bottom: 44px;
            padding: 11px 22px;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .08);
        }

        h1 {
            font-size: clamp(50px, 5.2vw, 76px);
            font-weight: 900;
            letter-spacing: 0;
            line-height: 1.35;
            margin: 0 0 20px;
        }

        h1 span {
            color: var(--yellow);
            display: block;
        }

        .subtitle {
            color: rgba(255, 255, 255, .92);
            font-size: clamp(27px, 2.6vw, 42px);
            font-weight: 700;
            line-height: 1.35;
            margin: 0 0 28px;
        }

        .lead {
            color: rgba(255, 255, 255, .9);
            font-size: 21px;
            font-weight: 700;
            line-height: 1.75;
            margin: 0;
            max-width: 650px;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 34px;
        }

        .hero-btn {
            align-items: center;
            border-radius: 18px;
            display: inline-flex;
            font-size: 20px;
            font-weight: 900;
            gap: 13px;
            min-height: 72px;
            padding: 0 44px;
        }

        .hero-btn.primary {
            background: #fff;
            color: var(--blue);
        }

        .hero-btn.secondary {
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .22);
            color: #fff;
            min-width: 298px;
            justify-content: center;
        }

        .hero-rule {
            background: rgba(255, 255, 255, .18);
            height: 1px;
            margin-top: 60px;
            width: 100%;
        }

        .art {
            align-self: center;
            min-height: 520px;
            position: relative;
        }

        .table {
            background: #bd6538;
            border-radius: 4px;
            bottom: 115px;
            height: 170px;
            left: 11%;
            position: absolute;
            transform: skewY(-8deg);
            width: 78%;
        }

        .table::before {
            background: #8f482d;
            bottom: -128px;
            content: "";
            height: 138px;
            left: 47%;
            position: absolute;
            transform: skewY(8deg);
            width: 22px;
        }

        .table::after {
            background: #d98a56;
            content: "";
            height: 32px;
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
        }

        .shadow {
            background: #d5d7d7;
            border-radius: 50%;
            bottom: 42px;
            height: 92px;
            left: 12%;
            position: absolute;
            transform: rotate(-4deg);
            width: 72%;
        }

        .monitor {
            background: #202631;
            border-radius: 4px;
            box-shadow: 0 10px 0 #111827;
            height: 124px;
            position: absolute;
            top: 142px;
            width: 142px;
            z-index: 5;
        }

        .monitor::before {
            background: #e8f4ff;
            content: "";
            height: 96px;
            left: 11px;
            position: absolute;
            top: 10px;
            width: 120px;
        }

        .monitor::after {
            background: #222;
            bottom: -56px;
            content: "";
            height: 48px;
            left: 62px;
            position: absolute;
            width: 18px;
        }

        .screen-lines {
            background:
                linear-gradient(#f23b3b 0 0) 12px 16px / 42px 5px no-repeat,
                linear-gradient(#37c56a 0 0) 20px 42px / 28px 7px no-repeat,
                linear-gradient(#d0d8e7 0 0) 66px 20px / 40px 5px no-repeat,
                linear-gradient(#d0d8e7 0 0) 66px 36px / 34px 5px no-repeat;
            height: 96px;
            left: 11px;
            position: absolute;
            top: 10px;
            width: 120px;
            z-index: 6;
        }

        .monitor.one {
            left: 22%;
        }

        .monitor.two {
            left: 43%;
            top: 154px;
        }

        .monitor.three {
            left: 62%;
            top: 130px;
        }

        .person {
            position: absolute;
            z-index: 6;
        }

        .head {
            border-radius: 50%;
            height: 58px;
            position: absolute;
            width: 58px;
        }

        .body {
            border-radius: 34px 34px 12px 12px;
            height: 120px;
            position: absolute;
            width: 110px;
        }

        .person.left {
            bottom: 50px;
            height: 260px;
            left: 7%;
            width: 210px;
        }

        .person.left .head {
            background: #5d1d13;
            left: 74px;
            top: 24px;
        }

        .person.left .body {
            background: #1f75a3;
            left: 56px;
            top: 82px;
            transform: rotate(-18deg);
        }

        .person.left::before {
            background: #ef3330;
            border-radius: 22px;
            bottom: 8px;
            content: "";
            height: 190px;
            left: 14px;
            position: absolute;
            transform: rotate(-7deg);
            width: 74px;
        }

        .person.left::after {
            background: #182334;
            border-radius: 18px;
            bottom: 34px;
            content: "";
            height: 120px;
            left: 98px;
            position: absolute;
            transform: rotate(-10deg);
            width: 48px;
        }

        .person.top {
            height: 210px;
            left: 43%;
            top: 56px;
            width: 190px;
        }

        .person.top .head {
            background: #242126;
            left: 74px;
            top: 0;
        }

        .person.top .body {
            background: #f73333;
            left: 48px;
            top: 54px;
            transform: rotate(-12deg);
        }

        .person.right {
            bottom: 54px;
            height: 270px;
            right: 9%;
            width: 230px;
        }

        .person.right .head {
            background: #9c3f17;
            right: 72px;
            top: 16px;
        }

        .person.right .body {
            background: #72c72d;
            right: 48px;
            top: 90px;
            transform: rotate(16deg);
        }

        .person.right::before {
            background: #49bfb0;
            border-radius: 24px 24px 52px 52px;
            bottom: 16px;
            content: "";
            height: 200px;
            right: 2px;
            position: absolute;
            transform: rotate(3deg);
            width: 86px;
        }

        .person.right::after {
            background: #1b2230;
            border-radius: 20px;
            bottom: 36px;
            content: "";
            height: 130px;
            right: 106px;
            position: absolute;
            transform: rotate(13deg);
            width: 54px;
        }

        .person.back {
            height: 190px;
            right: 7%;
            top: 58px;
            width: 170px;
            z-index: 3;
        }

        .person.back .head {
            background: #ad481b;
            left: 50px;
            top: 0;
        }

        .person.back .body {
            background: #56caba;
            left: 28px;
            top: 54px;
            transform: rotate(6deg);
        }

        .keyboard {
            background: #222;
            border-radius: 8px;
            bottom: 130px;
            height: 34px;
            position: absolute;
            width: 94px;
            z-index: 7;
        }

        .keyboard.one {
            left: 31%;
            transform: rotate(12deg);
        }

        .keyboard.two {
            right: 27%;
            transform: rotate(-12deg);
        }

        .telegram-float {
            align-items: center;
            background: var(--blue);
            border-radius: 20px;
            bottom: 22px;
            box-shadow: 0 12px 26px rgba(15, 23, 42, .18);
            color: #fff;
            display: inline-flex;
            height: 70px;
            justify-content: center;
            position: absolute;
            right: 30px;
            width: 70px;
            z-index: 8;
        }

        .telegram-float .dot {
            position: absolute;
            right: -4px;
            top: -4px;
        }

        .lesson-section {
            background: #f4f8fc;
            padding: 70px 48px;
        }

        .lesson-wrap {
            margin: 0 auto;
            max-width: 1320px;
        }

        .lesson-heading {
            align-items: end;
            display: flex;
            gap: 24px;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .lesson-heading h2 {
            color: #123047;
            font-size: clamp(30px, 3vw, 46px);
            font-weight: 900;
            letter-spacing: 0;
            line-height: 1.25;
            margin: 0;
        }

        .lesson-heading p {
            color: var(--muted);
            font-size: 18px;
            font-weight: 700;
            line-height: 1.6;
            margin: 0;
            max-width: 560px;
        }

        .course-block {
            background: #fff;
            border: 1px solid #dce8f2;
            border-radius: 12px;
            box-shadow: 0 14px 32px rgba(28, 87, 126, .08);
            margin-top: 22px;
            overflow: hidden;
        }

        .course-head {
            align-items: center;
            background: linear-gradient(135deg, #216d9f, #2795cf);
            color: #fff;
            display: flex;
            gap: 18px;
            justify-content: space-between;
            padding: 22px 26px;
        }

        .course-head h3 {
            font-size: 24px;
            font-weight: 900;
            line-height: 1.35;
            margin: 0;
        }

        .course-code {
            background: rgba(255, 255, 255, .16);
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 999px;
            font-family: "Inter", Arial, sans-serif;
            font-size: 13px;
            font-weight: 800;
            padding: 8px 12px;
            white-space: nowrap;
        }

        .module-block {
            padding: 24px 26px 28px;
        }

        .module-title {
            align-items: center;
            color: #123047;
            display: flex;
            font-size: 20px;
            font-weight: 900;
            gap: 10px;
            margin: 0 0 16px;
        }

        .lesson-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .lesson-card {
            border: 1px solid #dce8f2;
            border-radius: 10px;
            display: grid;
            gap: 14px;
            grid-template-columns: 52px minmax(0, 1fr);
            padding: 16px;
        }

        .lesson-icon {
            align-items: center;
            background: #e9f4fb;
            border-radius: 10px;
            color: var(--blue);
            display: inline-flex;
            height: 52px;
            justify-content: center;
            width: 52px;
        }

        .lesson-card h4 {
            color: #123047;
            font-size: 18px;
            font-weight: 900;
            line-height: 1.35;
            margin: 0 0 5px;
        }

        .lesson-card p {
            color: #5f6f82;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.55;
            margin: 0 0 10px;
        }

        .lesson-meta {
            align-items: center;
            color: #60758a;
            display: flex;
            flex-wrap: wrap;
            font-family: "Inter", Arial, sans-serif;
            font-size: 13px;
            font-weight: 700;
            gap: 8px;
        }

        .lesson-link {
            color: var(--blue);
            font-weight: 900;
        }

        .empty-lessons {
            background: #fff;
            border: 1px dashed #bdd5e8;
            border-radius: 12px;
            color: #60758a;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.7;
            padding: 28px;
        }

        .course-list-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-bottom: 28px;
        }

        .course-select-card {
            background: #fff;
            border: 1px solid #dce8f2;
            border-radius: 12px;
            box-shadow: 0 14px 32px rgba(28, 87, 126, .08);
            cursor: pointer;
            min-height: 170px;
            padding: 22px;
            text-align: left;
            transition: .2s ease;
            width: 100%;
        }

        .course-select-card:hover,
        .course-select-card.active {
            border-color: var(--blue);
            box-shadow: 0 18px 34px rgba(33, 109, 159, .16);
            transform: translateY(-2px);
        }

        .course-select-card .course-icon {
            align-items: center;
            background: #e9f4fb;
            border-radius: 12px;
            color: var(--blue);
            display: inline-flex;
            height: 48px;
            justify-content: center;
            margin-bottom: 14px;
            width: 48px;
        }

        .course-select-card h3 {
            color: #123047;
            font-family: "Battambang", "Inter", Arial, sans-serif;
            font-size: 21px;
            font-weight: 900;
            line-height: 1.35;
            margin: 0 0 8px;
        }

        .course-select-card p {
            color: #60758a;
            font-family: "Battambang", "Inter", Arial, sans-serif;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.55;
            margin: 0;
        }

        .course-detail {
            display: none;
        }

        .course-detail.active {
            display: block;
        }

        .course-intro {
            background: #f6fbff;
            border-bottom: 1px solid #dce8f2;
            color: #60758a;
            font-size: 17px;
            font-weight: 700;
            line-height: 1.7;
            padding: 22px 26px;
        }

        @media (max-width: 1120px) {
            .nav {
                grid-template-columns: 1fr;
                gap: 14px;
                padding: 18px 24px;
            }

            .menu,
            .actions {
                justify-content: flex-start;
                overflow-x: auto;
            }

            .hero-inner {
                grid-template-columns: 1fr;
                padding: 28px 24px 60px;
            }

            .copy {
                max-width: 760px;
            }

            .art {
                min-height: 430px;
            }

            .lesson-section {
                padding: 52px 24px;
            }

            .lesson-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .lesson-grid {
                grid-template-columns: 1fr;
            }

            .course-list-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 680px) {
            .promo {
                font-size: 14px;
            }

            .brand strong {
                font-size: 19px;
            }

            .menu a,
            .login,
            .cta {
                font-size: 15px;
            }

            .hero-inner {
                gap: 24px;
            }

            .pill {
                font-size: 15px;
                margin-bottom: 24px;
            }

            h1 {
                font-size: 40px;
            }

            .subtitle {
                font-size: 25px;
            }

            .lead {
                font-size: 17px;
            }

            .hero-btn {
                min-height: 58px;
                padding: 0 24px;
                width: 100%;
            }

            .hero-btn.secondary {
                min-width: 0;
            }

            .art {
                transform: scale(.76);
                transform-origin: top center;
            }
        }
    </style>
</head>

<body>
    <div class="promo">
        <span class="dot"></span>
        <span>🚀 រៀនសរសេរកូដ - ចាប់ផ្តើមថ្ងៃនេះ! ចុះឈ្មោះឥឡូវនេះ →</span>
    </div>

    <header class="nav-wrap">
        <div class="nav">
            <a class="brand" href="{{ route('moodle.test') }}">
                <span class="brand-logo">
                    <img src="{{ asset('backend/dist/img/spilogo.png') }}" alt="CodeKhmerVideo">
                </span>
                <span>
                    <strong>CodeKhmerVideo</strong>
                    <span>រៀនសរសេរកូដ</span>
                </span>
            </a>

            <nav class="menu" aria-label="Main menu">
                <a class="active" href="#">ទំព័រដើម</a>
                <a href="#">វគ្គសិក្សា</a>
                <a href="#">សេវាកម្ម</a>
                <a href="#">អត្ថបទ <i class="fas fa-external-link-alt" style="font-size: 12px;"></i></a>
                <a href="#">អំពីយើង</a>
                <a href="#">ទំនាក់ទំនង</a>
            </nav>

            <div class="actions">
                <a class="login" href="#">ចូល</a>
                <a class="cta" href="#">ចុះឈ្មោះ</a>
            </div>
        </div>
    </header>

    <main class="hero">
        <div class="hero-inner">
            <section class="copy">
                <div class="pill">
                    <span class="dot"></span>
                    <span>🚀 កម្មវិធីរៀនសរសេរកូដ</span>
                </div>

                <h1>
                    អ្នកអាចរៀនសរសេរ
                    <span>ចាប់ផ្តើមពីនេះ!</span>
                </h1>

                <p class="subtitle">ពីអ្នកដំបូងទៅកាន់អ្នកអភិវឌ្ឍន៍</p>

                <p class="lead">
                    សាកល្បងទំព័រ Static សម្រាប់ប្រព័ន្ធ LMS របស់អ្នក។ ទំព័រនេះមានរចនាប័ទ្មដូចគំរូ
                    ដោយមានប៊ូតុងចុះឈ្មោះ ការណែនាំវគ្គសិក្សា និងការភ្ជាប់ទៅ Telegram។
                </p>

                <div class="hero-buttons">
                    <a class="hero-btn primary" href="#courses"><i class="fas fa-play"></i> ចាប់ផ្តើមរៀន →</a>
                    <a class="hero-btn secondary" href="#"><i class="fab fa-telegram-plane"></i> ទាក់ទងតាម Telegram</a>
                </div>

                <div class="hero-rule"></div>
            </section>

            <section class="art" aria-label="Students learning programming illustration">
                <div class="shadow"></div>
                <div class="person back">
                    <span class="head"></span>
                    <span class="body"></span>
                </div>
                <div class="person top">
                    <span class="head"></span>
                    <span class="body"></span>
                </div>
                <div class="table"></div>
                <div class="monitor one"><span class="screen-lines"></span></div>
                <div class="monitor two"><span class="screen-lines"></span></div>
                <div class="monitor three"><span class="screen-lines"></span></div>
                <div class="keyboard one"></div>
                <div class="keyboard two"></div>
                <div class="person left">
                    <span class="head"></span>
                    <span class="body"></span>
                </div>
                <div class="person right">
                    <span class="head"></span>
                    <span class="body"></span>
                </div>
            </section>
        </div>

        <a class="telegram-float" href="#" aria-label="Telegram">
            <span class="dot"></span>
            <i class="fab fa-telegram-plane fa-2x"></i>
        </a>
    </main>

    <section class="lesson-section" id="courses">
        <div class="lesson-wrap">
            <div class="lesson-heading">
                <div>
                    <h2>Course List</h2>
                </div>
                <p>Click a course first, then view its introduction and Moodle-style modules with lesson contents.</p>
            </div>

            @if ($courses->isNotEmpty())
                <div class="course-list-grid">
                    @foreach ($courses as $course)
                        <button class="course-select-card {{ $loop->first ? 'active' : '' }}" type="button" data-course-target="course-{{ $course->id }}">
                            <span class="course-icon"><i class="fas fa-graduation-cap"></i></span>
                            <h3>{{ $course->title }}</h3>
                            <p>{{ $course->lessonContents->count() }} lessons - {{ $course->lessonContents->groupBy('module_number')->count() }} modules</p>
                        </button>
                    @endforeach
                </div>

                @foreach ($courses as $course)
                    <article class="course-block course-detail {{ $loop->first ? 'active' : '' }}" id="course-{{ $course->id }}">
                        <div class="course-head">
                            <h3>{{ $course->title }}</h3>
                            <span class="course-code">{{ $course->code ?? 'COURSE-' . $course->id }}</span>
                        </div>

                        <div class="course-intro">
                            {{ $course->description ?: 'Course introduction: start here to understand the course goals, weekly modules, activities, and requirements.' }}
                        </div>

                        @foreach ($course->lessonContents->groupBy('module_number') as $moduleNumber => $lessons)
                            @php
                                $moduleTitle = $lessons->first()->module_title ?: 'Module ' . $moduleNumber;
                            @endphp

                            <div class="module-block">
                                <h3 class="module-title">
                                    <i class="fas fa-layer-group"></i>
                                    Module {{ $moduleNumber }} - {{ $moduleTitle }}
                                </h3>

                                <div class="lesson-grid">
                                    @foreach ($lessons as $lesson)
                                        @php
                                            $icon = [
                                                'lesson' => 'fa-book-open',
                                                'page' => 'fa-file-alt',
                                                'video' => 'fa-play-circle',
                                                'file' => 'fa-paperclip',
                                                'url' => 'fa-link',
                                                'assignment' => 'fa-clipboard-check',
                                                'quiz' => 'fa-question-circle',
                                                'forum' => 'fa-comments',
                                            ][$lesson->content_type] ?? 'fa-book-open';
                                        @endphp

                                        <div class="lesson-card">
                                            <span class="lesson-icon">
                                                <i class="fas {{ $icon }}"></i>
                                            </span>
                                            <div>
                                                <h4>{{ $lesson->title }}</h4>
                                                @if ($lesson->summary)
                                                    <p>{{ $lesson->summary }}</p>
                                                @elseif ($lesson->body)
                                                    <p>{{ Str::limit(strip_tags($lesson->body), 140) }}</p>
                                                @endif

                                                <div class="lesson-meta">
                                                    <span>{{ ucfirst($lesson->content_type) }}</span>
                                                    @if ($lesson->duration_minutes)
                                                        <span>- {{ $lesson->duration_minutes }} min</span>
                                                    @endif
                                                    @if ($lesson->completion_required)
                                                        <span>- Required</span>
                                                    @endif
                                                    @if ($lesson->video_url)
                                                        <a class="lesson-link" href="{{ $lesson->video_url }}" target="_blank" rel="noopener">Watch</a>
                                                    @elseif ($lesson->external_url)
                                                        <a class="lesson-link" href="{{ $lesson->external_url }}" target="_blank" rel="noopener">Open</a>
                                                    @elseif ($lesson->file_path)
                                                        <a class="lesson-link" href="{{ asset($lesson->file_path) }}" target="_blank" rel="noopener">File</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </article>
                @endforeach
            @else
                <div class="course-list-grid">
                    <button class="course-select-card active" type="button" data-course-target="static-web">
                        <span class="course-icon"><i class="fas fa-code"></i></span>
                        <h3>Web Development Basics</h3>
                        <p>15 lessons - 5 modules</p>
                    </button>

                    <button class="course-select-card" type="button" data-course-target="static-laravel">
                        <span class="course-icon"><i class="fas fa-laptop-code"></i></span>
                        <h3>Laravel LMS Project</h3>
                        <p>12 lessons - 4 modules</p>
                    </button>

                    <button class="course-select-card" type="button" data-course-target="static-design">
                        <span class="course-icon"><i class="fas fa-palette"></i></span>
                        <h3>UI Design Foundation</h3>
                        <p>10 lessons - 3 modules</p>
                    </button>
                </div>

                <article class="course-block course-detail active" id="static-web">
                    <div class="course-head">
                        <h3>Web Development Basics</h3>
                        <span class="course-code">WEB-101</span>
                    </div>

                    <div class="course-intro">
                        Course introduction: learn how websites work, how to write HTML/CSS, and how to publish your first simple web pages.
                    </div>

                    <div class="module-block">
                        <h3 class="module-title">
                            <i class="fas fa-layer-group"></i>
                            Module 1 - Introduction to Web Development
                        </h3>

                        <div class="lesson-grid">
                            <div class="lesson-card">
                                <span class="lesson-icon">
                                    <i class="fas fa-book-open"></i>
                                </span>
                                <div>
                                    <h4>Lesson 1: What is HTML?</h4>
                                    <p>Learn the basic structure of a web page, common HTML tags, and how content is displayed in the browser.</p>
                                    <div class="lesson-meta">
                                        <span>Lesson</span>
                                        <span>- 15 min</span>
                                        <span>- Required</span>
                                    </div>
                                </div>
                            </div>

                            <div class="lesson-card">
                                <span class="lesson-icon">
                                    <i class="fas fa-play-circle"></i>
                                </span>
                                <div>
                                    <h4>Video: Build Your First Page</h4>
                                    <p>Watch a short practical demo that creates a simple HTML page with headings, paragraphs, and links.</p>
                                    <div class="lesson-meta">
                                        <span>Video</span>
                                        <span>- 12 min</span>
                                        <a class="lesson-link" href="#">Watch</a>
                                    </div>
                                </div>
                            </div>

                            <div class="lesson-card">
                                <span class="lesson-icon">
                                    <i class="fas fa-paperclip"></i>
                                </span>
                                <div>
                                    <h4>Download: HTML Cheat Sheet</h4>
                                    <p>A quick reference file for common tags, attributes, and page structure.</p>
                                    <div class="lesson-meta">
                                        <span>File</span>
                                        <a class="lesson-link" href="#">File</a>
                                    </div>
                                </div>
                            </div>

                            <div class="lesson-card">
                                <span class="lesson-icon">
                                    <i class="fas fa-clipboard-check"></i>
                                </span>
                                <div>
                                    <h4>Assignment: Personal Profile Page</h4>
                                    <p>Create a simple personal profile page using HTML headings, image, list, and contact link.</p>
                                    <div class="lesson-meta">
                                        <span>Assignment</span>
                                        <span>- Required</span>
                                        <span>- 100 pts</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach ([2 => 'CSS Layout and Styling', 3 => 'JavaScript Basics', 4 => 'Forms and Validation', 5 => 'Final Mini Project'] as $moduleNumber => $moduleTitle)
                        <div class="module-block">
                            <h3 class="module-title">
                                <i class="fas fa-layer-group"></i>
                                Module {{ $moduleNumber }} - {{ $moduleTitle }}
                            </h3>
                            <div class="lesson-grid">
                                <div class="lesson-card">
                                    <span class="lesson-icon"><i class="fas fa-book-open"></i></span>
                                    <div>
                                        <h4>Lesson {{ $moduleNumber }}.1: {{ $moduleTitle }}</h4>
                                        <p>Study the key concept, read the notes, and review the short examples for this module.</p>
                                        <div class="lesson-meta">
                                            <span>Lesson</span>
                                            <span>- 20 min</span>
                                            <span>- Required</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="lesson-card">
                                    <span class="lesson-icon"><i class="fas fa-question-circle"></i></span>
                                    <div>
                                        <h4>Quiz {{ $moduleNumber }}: Knowledge Check</h4>
                                        <p>Answer a short quiz before moving to the next module.</p>
                                        <div class="lesson-meta">
                                            <span>Quiz</span>
                                            <span>- 10 min</span>
                                            <span>- 20 pts</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </article>

                <article class="course-block course-detail" id="static-laravel">
                    <div class="course-head">
                        <h3>Laravel LMS Project</h3>
                        <span class="course-code">LMS-201</span>
                    </div>
                    <div class="course-intro">
                        Course introduction: build an LMS project step by step using Laravel routes, controllers, Blade views, migrations, and CRUD.
                    </div>
                    @foreach ([1 => 'Laravel Setup', 2 => 'Database and Models', 3 => 'Admin CRUD', 4 => 'Frontend Course Page'] as $moduleNumber => $moduleTitle)
                        <div class="module-block">
                            <h3 class="module-title"><i class="fas fa-layer-group"></i> Module {{ $moduleNumber }} - {{ $moduleTitle }}</h3>
                            <div class="lesson-grid">
                                <div class="lesson-card">
                                    <span class="lesson-icon"><i class="fas fa-play-circle"></i></span>
                                    <div>
                                        <h4>Video: {{ $moduleTitle }}</h4>
                                        <p>Follow a practical walkthrough for this part of the LMS build.</p>
                                        <div class="lesson-meta"><span>Video</span><span>- 18 min</span><a class="lesson-link" href="#">Watch</a></div>
                                    </div>
                                </div>
                                <div class="lesson-card">
                                    <span class="lesson-icon"><i class="fas fa-clipboard-check"></i></span>
                                    <div>
                                        <h4>Assignment: Complete Module {{ $moduleNumber }}</h4>
                                        <p>Implement the feature and submit your result for review.</p>
                                        <div class="lesson-meta"><span>Assignment</span><span>- Required</span><span>- 100 pts</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </article>

                <article class="course-block course-detail" id="static-design">
                    <div class="course-head">
                        <h3>UI Design Foundation</h3>
                        <span class="course-code">UI-110</span>
                    </div>
                    <div class="course-intro">
                        Course introduction: learn spacing, typography, color, components, and dashboard layout for LMS interfaces.
                    </div>
                    @foreach ([1 => 'Design Principles', 2 => 'Components', 3 => 'Dashboard Layout'] as $moduleNumber => $moduleTitle)
                        <div class="module-block">
                            <h3 class="module-title"><i class="fas fa-layer-group"></i> Module {{ $moduleNumber }} - {{ $moduleTitle }}</h3>
                            <div class="lesson-grid">
                                <div class="lesson-card">
                                    <span class="lesson-icon"><i class="fas fa-file-alt"></i></span>
                                    <div>
                                        <h4>Reading: {{ $moduleTitle }}</h4>
                                        <p>Review examples and apply the design rules to a simple LMS page.</p>
                                        <div class="lesson-meta"><span>Page</span><span>- 15 min</span></div>
                                    </div>
                                </div>
                                <div class="lesson-card">
                                    <span class="lesson-icon"><i class="fas fa-comments"></i></span>
                                    <div>
                                        <h4>Forum: Share Your UI</h4>
                                        <p>Post your design screenshot and give feedback to classmates.</p>
                                        <div class="lesson-meta"><span>Forum</span><span>- Required</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </article>
            @endif
        </div>
    </section>

    <script>
        document.querySelectorAll('[data-course-target]').forEach(function(button) {
            button.addEventListener('click', function() {
                var targetId = button.getAttribute('data-course-target');

                document.querySelectorAll('[data-course-target]').forEach(function(item) {
                    item.classList.toggle('active', item === button);
                });

                document.querySelectorAll('.course-detail').forEach(function(panel) {
                    panel.classList.toggle('active', panel.id === targetId);
                });

                document.getElementById(targetId)?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });
    </script>
</body>

</html>
