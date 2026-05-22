<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ផ្ទៀងផ្ទាត់ OTP | LMS</title>
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <b>OTP</b> Verify
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">បញ្ចូលលេខកូដ 6 ខ្ទង់ដែលបានផ្ញើទៅអ៊ីមែលរបស់អ្នក</p>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('two-factor.verify') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="otp" class="form-control" placeholder="OTP Code"
                            maxlength="6" inputmode="numeric" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">ផ្ទៀងផ្ទាត់</button>
                </form>

                <form action="{{ route('two-factor.resend') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-link btn-block">ផ្ញើ OTP ម្តងទៀត</button>
                </form>

                <form action="{{ route('logout') }}" method="POST" class="mt-1">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger btn-block">ចាកចេញ</button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>
</body>

</html>
