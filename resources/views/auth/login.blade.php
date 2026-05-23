<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ចូលប្រព័ន្ធ | LMS</title>

    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: #111827;
            background: #f4f7fb;
            font-family: 'Khmer OS Battambang', 'Noto Sans Khmer', Arial, sans-serif;
        }

        .login-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(420px, 0.8fr);
            background: #ffffff;
        }

        .login-image {
            position: relative;
            height: 100vh;
            min-height: 100vh;
            overflow: hidden;
            background: #15213a;
        }

        .login-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .login-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(12, 20, 38, 0.78), rgba(12, 20, 38, 0.28));
        }

        .image-copy {
            position: absolute;
            left: clamp(32px, 6vw, 86px);
            bottom: clamp(40px, 8vw, 96px);
            z-index: 1;
            max-width: 560px;
            color: #ffffff;
        }

        .image-copy h1 {
            margin: 0 0 18px;
            font-size: clamp(38px, 5vw, 64px);
            line-height: 1.15;
            font-weight: 800;
        }

        .image-copy p {
            margin: 0;
            max-width: 480px;
            font-size: 18px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.88);
        }

        .login-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 42px;
            background: #ffffff;
        }

        .login-card {
            width: 100%;
            max-width: 390px;
        }

        .brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand img {
            width: 82px;
            height: 82px;
            object-fit: contain;
            margin-bottom: 16px;
        }

        .brand h2 {
            margin: 0 0 8px;
            font-size: 31px;
            line-height: 1.35;
            font-weight: 800;
        }

        .brand p {
            margin: 0;
            color: #6b7280;
            font-size: 15px;
            line-height: 1.6;
        }

        .field {
            position: relative;
            margin-bottom: 18px;
        }

        .field i {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: #7b8190;
            font-size: 15px;
            pointer-events: none;
        }

        .form-control {
            height: 54px;
            border: 1px solid #d6dbe4;
            border-radius: 8px;
            padding-left: 44px;
            font-size: 15px;
        }

        .form-control:focus {
            border-color: #3b00bf;
            box-shadow: 0 0 0 0.14rem rgba(59, 0, 191, 0.13);
        }

        .btn-login {
            height: 54px;
            border: 0;
            border-radius: 8px;
            background: #3b00bf;
            font-weight: 800;
            font-size: 16px;
        }

        .btn-login:hover,
        .btn-login:focus {
            background: #2f0099;
        }

        .alert {
            border-radius: 8px;
            font-size: 14px;
        }

        .footer-text {
            margin-top: 28px;
            text-align: center;
            color: #8a909d;
            font-size: 13px;
            line-height: 1.7;
        }

        @media (max-width: 992px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .login-image {
                height: 240px;
                min-height: 240px;
            }

            .image-copy {
                left: 24px;
                right: 24px;
                bottom: 24px;
            }

            .image-copy h1 {
                font-size: 34px;
            }

            .image-copy p {
                font-size: 15px;
            }

            .login-panel {
                min-height: calc(100vh - 240px);
                padding: 32px 18px;
            }
        }
    </style>
</head>

<body>
    <main class="login-shell">
        <section class="login-image" aria-label="Campus">
            <img src="{{ asset('backend/dist/img/slide/SPI-Campus-no-logo-Crop.png') }}" alt="SPI Campus">
            <div class="image-copy">
                <h1>Learning Management System</h1>
                <p>ប្រព័ន្ធគ្រប់គ្រងការសិក្សា សម្រាប់គ្រប់គ្រងព័ត៌មាន និងដំណើរការសិក្សារបស់អ្នក។</p>
            </div>
        </section>

        <section class="login-panel">
            <div class="login-card">
                <div class="brand">
                    <img src="{{ asset('backend/dist/img/spilogo.png') }}" alt="LMS">
                    <h2>ចូលប្រព័ន្ធ</h2>
                    <p>សូមបញ្ចូលព័ត៌មានគណនីរបស់អ្នក</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST">
                    @csrf

                    <div class="field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="login" class="form-control" placeholder="Username ឬ Email"
                            value="{{ old('login') }}" required autofocus>
                    </div>

                    <div class="field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-login">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        ចូលប្រព័ន្ធ
                    </button>
                </form>

                <div class="footer-text">
                    © {{ date('Y') }} LMS System<br>
                    រក្សាសិទ្ធិគ្រប់យ៉ាង
                </div>
            </div>
        </section>
    </main>

    <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>
</body>

</html>
