<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ផ្ទាំងគ្រប់គ្រង | LMS')</title>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    {{-- Optional plugins --}}
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .dataTables_processing,
        div.dt-processing {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 20;
            width: auto;
            min-width: 160px;
            height: auto;
            margin: 0;
            padding: .75rem 1rem;
            border: 0;
            border-radius: .35rem;
            background: rgba(255, 255, 255, 0.82);
            box-shadow: 0 .35rem 1rem rgba(15, 23, 42, .12);
            text-align: center;
            transform: translate(-50%, -50%);
        }

        .dataTables_processing:empty,
        div.dt-processing:empty {
            display: none !important;
        }

        .dt-loading {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            color: #1f2937;
            font-weight: 600;
        }

        .dt-loading::before {
            width: 1rem;
            height: 1rem;
            content: "";
            border: 2px solid #cbd5e1;
            border-top-color: #2563eb;
            border-radius: 50%;
            animation: dt-spin .75s linear infinite;
        }

        @keyframes dt-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .badge-purple {
            background: #6f42c1;
            color: #fff;
        }

        .dataTables_wrapper,
        .dt-container {
            position: relative;
        }

        .lms-dt-loader {
            align-items: center;
            background: rgba(255, 255, 255, .7);
            display: none;
            inset: 0;
            justify-content: center;
            pointer-events: none;
            position: absolute;
            z-index: 30;
        }

        .lms-dt-loader.is-visible {
            display: flex;
        }

        .lms-dt-loader > div {
            display: inline-flex;
            gap: 8px;
        }

        .lms-dt-loader > div > div {
            animation: lms-dt-pulse .75s ease-in-out infinite;
            background: #1479e8;
            border-radius: 50%;
            height: 12px;
            width: 12px;
        }

        .lms-dt-loader > div > div:nth-child(2) {
            animation-delay: .1s;
        }

        .lms-dt-loader > div > div:nth-child(3) {
            animation-delay: .2s;
        }

        .lms-dt-loader > div > div:nth-child(4) {
            animation-delay: .3s;
        }

        @keyframes lms-dt-pulse {
            0%,
            100% {
                opacity: .35;
                transform: scale(.72);
            }

            50% {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

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
    <div class="modal fade" id="globalDeleteModal" tabindex="-1" role="dialog" aria-labelledby="globalDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="globalDeleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="globalDeleteConfirmBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        window.jQuery || document.write('<script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"><\/script>');
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        if (typeof $.fn.modal === 'undefined') {
            document.write('<script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"><\/script>');
        }
    </script>

    <script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>

    <script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.8/js/dataTables.bootstrap4.js"></script>
    <script>
        if (typeof DataTable === 'undefined' && ! $.fn.DataTable) {
            document.write('<script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"><\/script>');
            document.write('<script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"><\/script>');
        }
    </script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            if ($.fn.DataTable) {
                $.extend(true, $.fn.dataTable.defaults, {
                    processing: false,
                    responsive: true,
                    autoWidth: false,
                    pageLength: 10,
                    language: {
                        processing: '',
                        search: 'ស្វែងរក:',
                        lengthMenu: 'បង្ហាញ _MENU_ ជួរ',
                        info: 'បង្ហាញ _START_ ដល់ _END_ នៃ _TOTAL_ ជួរ',
                        infoEmpty: 'បង្ហាញ 0 ដល់ 0 នៃ 0 ជួរ',
                        zeroRecords: 'រកមិនឃើញទិន្នន័យដែលត្រូវគ្នា',
                        emptyTable: 'មិនមានទិន្នន័យ',
                        paginate: {
                            first: 'ដំបូង',
                            previous: 'មុន',
                            next: 'បន្ទាប់',
                            last: 'ចុងក្រោយ'
                        }
                    }
                });
            }

            if ($.fn.DataTable) {
                window.LmsDataTableDefaults = {
                    processing: false,
                    responsive: true,
                    autoWidth: false,
                    pageLength: 10,
                    language: {
                        processing: '',
                        search: 'ស្វែងរក:',
                        lengthMenu: 'បង្ហាញ _MENU_ ជួរ',
                        info: 'បង្ហាញ _START_ ដល់ _END_ នៃ _TOTAL_ ជួរ',
                        infoEmpty: 'បង្ហាញ 0 ដល់ 0 នៃ 0 ជួរ',
                        zeroRecords: 'រកមិនឃើញទិន្នន័យដែលត្រូវគ្នា',
                        emptyTable: 'មិនមានទិន្នន័យ',
                        paginate: {
                            first: 'ដំបូង',
                            previous: 'មុន',
                            next: 'បន្ទាប់',
                            last: 'ចុងក្រោយ'
                        }
                    }
                };

                $.extend(true, $.fn.dataTable.defaults, window.LmsDataTableDefaults);

                window.attachLmsDataTableLoader = function(table) {
                    const $table = $(table);

                    if ($table.data('lms-loader-attached')) {
                        return;
                    }

                    $table.data('lms-loader-attached', true);

                    function wrapper() {
                        return $table.closest('.dataTables_wrapper, .dt-container');
                    }

                    function loader() {
                        const $wrapper = wrapper();

                        if (! $wrapper.length) {
                            return $();
                        }

                        let $loader = $wrapper.children('.lms-dt-loader');

                        if (! $loader.length) {
                            $loader = $('<div class="lms-dt-loader"><div><div></div><div></div><div></div><div></div></div></div>');
                            $wrapper.append($loader);
                        }

                        return $loader;
                    }

                    function showLoader() {
                        const $loader = loader();

                        if ($loader.length) {
                            clearTimeout($table.data('lms-loader-timer'));
                            $loader.addClass('is-visible');
                        }
                    }

                    function hideLoader(delay = 160) {
                        const timer = setTimeout(function() {
                            loader().removeClass('is-visible');
                        }, delay);

                        $table.data('lms-loader-timer', timer);
                    }

                    $table.on('search.dt order.dt page.dt length.dt', showLoader);
                    $table.on('draw.dt', function() {
                        hideLoader();
                    });
                    $table.on('processing.dt', function(event, settings, processing) {
                        processing ? showLoader() : hideLoader(80);
                    });
                };

                $(document).on('init.dt', function(event, settings) {
                    if (settings?.nTable) {
                        window.attachLmsDataTableLoader(settings.nTable);
                    }
                });
            }

            function refreshNotifications() {
                $.get('{{ route('admin.notifications.dropdown') }}', function (response) {
                    $('.js-notification-count').text(response.count);
                    $('.js-notification-list').html(response.html);
                });
            }

            $(document).on('click', '.js-notification-link', function (event) {
                event.preventDefault();
                const id = $(this).data('id');
                const href = $(this).attr('href');

                if (id) {
                    $.post('{{ url('/admin/notifications') }}/' + id + '/read').always(function () {
                        window.location.href = href;
                    });
                } else {
                    window.location.href = href;
                }
            });

            setInterval(refreshNotifications, 30000);

            $(document).on('click', '[data-delete-url]', function(event) {
                const $button = $(this);
                const url = $button.data('delete-url');

                if (! url) {
                    return;
                }

                event.preventDefault();
                event.stopImmediatePropagation();

                const modalSelector = $button.data('delete-modal') || '#deleteModal';
                const formSelector = $button.data('delete-form') || '#deleteForm';
                const name = $button.data('name') || $button.data('delete-name') || '';
                const $modal = $(modalSelector);
                const $form = $(formSelector);

                if ($form.length) {
                    $form.attr('action', url);
                }

                if ($modal.length) {
                    $modal.find('.modal-body b').first().text(name);
                    $modal.modal('show');
                    return;
                }

                pendingDeleteForm = null;
                $('#globalDeleteModal').data('delete-url', url).modal('show');
            });

            let pendingDeleteForm = null;

            $(document).on('submit', 'form', function(event) {
                const $form = $(this);
                const method = String($form.find('input[name="_method"]').val() || $form.attr('method') || '').toUpperCase();

                if (method !== 'DELETE' || $form.closest('.modal').length || $form.data('delete-confirmed')) {
                    return;
                }

                event.preventDefault();
                pendingDeleteForm = this;
                $('#globalDeleteModal').modal('show');
            });

            $('#globalDeleteConfirmBtn').on('click', function() {
                const deleteUrl = $('#globalDeleteModal').data('delete-url');

                if (deleteUrl) {
                    const form = $('<form>', {
                        method: 'POST',
                        action: deleteUrl
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: $('meta[name="csrf-token"]').attr('content')
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_method',
                        value: 'DELETE'
                    }));

                    $('#globalDeleteModal').removeData('delete-url').modal('hide');
                    $('body').append(form);
                    form.data('delete-confirmed', true);
                    form[0].submit();
                    return;
                }

                if (! pendingDeleteForm) {
                    return;
                }

                $(pendingDeleteForm).data('delete-confirmed', true);
                $('#globalDeleteModal').modal('hide');
                pendingDeleteForm.submit();
                pendingDeleteForm = null;
            });
        });
    </script>

    @stack('scripts')
    <script>
        $(function() {
            if (! $.fn.DataTable) {
                return;
            }

            $('table.table').each(function() {
                const table = this;
                const $table = $(table);

                if ($table.hasClass('no-datatable') || $table.closest('.mailbox-messages').length) {
                    return;
                }

                if (! $table.find('thead th').length || $table.find('tbody tr').length === 0) {
                    return;
                }

                if ($.fn.DataTable.isDataTable(table)) {
                    window.attachLmsDataTableLoader?.(table);
                    return;
                }

                $table.DataTable(window.LmsDataTableDefaults || {});
                window.attachLmsDataTableLoader?.(table);
            });
        });
    </script>
</body>

</html>
