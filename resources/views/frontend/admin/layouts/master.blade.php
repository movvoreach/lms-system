<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Loan System')</title>
    <link rel="icon" type="image/png" href="{{ asset('backend/dist/img/spilogo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@300;400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/css/admin-business.css') }}">

    @stack('styles')

    <style>
        body,
        .navbar,
        .nav-link,
        .dropdown-item,
        .navbar-brand,
        .dropdown-menu,
        .btn,
        p,
        span,
        div,
        a,
        table {
            font-family: 'Battambang', cursive !important;
        }

        label {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .form-control {
            border: 1px solid #d6dce5;
            border-radius: 6px;
            min-height: 40px;
            padding: 8px 12px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .12);
        }

        textarea.form-control {
            min-height: 96px;
        }

        input[type="file"].form-control {
            height: auto;
            padding: 7px 10px;
        }

        .card {
            border-radius: 6px;
        }

        .card-header {
            font-weight: 700;
        }

        .required-star {
            color: #dc3545;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="wrapper">


        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-10">
                        @include('admin.partials.header')
                        @yield('page-title')
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('backend/dist/js/admin-business.js') }}"></script>

    @stack('scripts')
</body>

</html>
