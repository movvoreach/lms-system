<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard | LMS')</title>
    <link rel="icon" type="image/png" href="{{ asset('backend/dist/img/spilogo.png') }}">
    {{-- ✅ Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&family=Battambang:wght@300;400;600;700&display=swap"
        rel="stylesheet">

    {{-- ✅ Font Awesome (must load BEFORE AdminLTE) --}}
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">

    {{-- ✅ AdminLTE --}}
    <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom-admin.css') }}">

    {{-- Optional plugins --}}
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    {{-- Your page styles --}}
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
        @include('admin.partials.header')
        @include('admin.partials.sidebar')

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    @yield('page-title')
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

    </div>

    {{-- ✅ JS order: jQuery -> Bootstrap -> plugins -> AdminLTE --}}
    <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>




<script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>


    <script>
        $(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
