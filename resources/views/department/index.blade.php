@extends('admin.layouts.master')

@section('title', 'គ្រប់គ្រងដេប៉ាតឺម៉ង់')

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <style>
        /* ===== TABLE FIX ===== */
        #departmentTable {
            width: 100% !important;
            table-layout: fixed;
        }

        #departmentTable td {
            word-break: break-word;
            white-space: normal;
        }

        /* Badge better UI */
        .badge-primary {
            font-size: 12px;
            padding: 5px 8px;
        }
    </style>
@endpush

@section('content')
    <div class="admin-business-page department-page">
        <section class="content-header mt-4 px-0">
            <div class="container-fluid px-0">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-7">
                        <h1 class="mb-1">គ្រប់គ្រងដេប៉ាតឺម៉ង់</h1>
                        <p class="text-muted mb-0">បង្កើត ពិនិត្យ កែប្រែ និងលុបដេប៉ាតឺម៉ង់តាមមហាវិទ្យាល័យ។</p>
                    </div>
                    <div class="col-sm-5">
                        <ol class="breadcrumb float-sm-right mb-0">
                            <li class="breadcrumb-item">
                                <a href="">

                                    ផ្ទាំងគ្រប់គ្រង
                                </a>
                            </li>
                            <li class="breadcrumb-item active">ដេប៉ាតឺម៉ង់</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <div class="card shadow-sm mt-2">

            <div class="card-header">
                <h3 class="card-title">បញ្ជីដេប៉ាតឺម៉ង់</h3>

                <div class="card-tools">
                    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary btn-sm">
                        បង្កើត
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- ✅ RESPONSIVE WRAPPER --}}
                <div class="table-responsive">

                    <table id="departmentTable" class="table table-bordered table-striped w-100">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>មហាវិទ្យាល័យ</th>
                                <th>កូដ</th>
                                <th>ឈ្មោះ</th>
                                <th>ព្រឹទ្ធបុរស</th>
                                <th>ថ្ងៃបង្កើត</th>
                                <th>សកម្មភាព</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($departments as $key => $department)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>{{ $department->faculty->faculty_name ?? 'N/A' }}</td>

                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $department->department_code }}
                                        </span>
                                    </td>

                                    <td>{{ $department->department_name }}</td>

                                    <td>{{ $department->deans ?? 'N/A' }}</td>

                                    <td>{{ $department->created_at?->format('Y-m-d H:i') }}</td>

                                    <td>
                                        <a href="{{ route('admin.departments.edit', $department->department_id) }}"
                                            class="btn btn-info btn-sm">
                                            Edit
                                        </a>

                                        <button class="btn btn-danger btn-sm deleteBtn"
                                            data-id="{{ $department->department_id }}"
                                            data-name="{{ $department->department_name }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>
            </div>
        </div>

        {{-- DELETE MODAL --}}
        <div class="modal fade" id="deleteModal">
            <div class="modal-dialog">
                <form method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')

                    <div class="modal-content">

                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Delete Department</h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                &times;
                            </button>
                        </div>

                        <div class="modal-body">
                            Are you sure delete <b id="deptName"></b>?
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-danger">
                                Delete
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    @endsection

    @push('scripts')
        <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

        <script>
            $(document).ready(function() {

                // ✅ FIXED DATATABLE (SEARCH + RESPONSIVE + WIDTH)
                $('#departmentTable').DataTable({
                    responsive: true,
                    autoWidth: false,
                    pageLength: 10,
                    order: [
                        [0, 'asc']
                    ],
                    columnDefs: [{
                        targets: -1,
                        orderable: false,
                        searchable: false
                    }],
                    language: {
                        search: "ស្វែងរក:",
                        lengthMenu: "បង្ហាញ _MENU_ ទិន្នន័យ",
                        info: "បង្ហាញ _START_ ដល់ _END_ ក្នុងចំណោម _TOTAL_",
                        zeroRecords: "មិនមានទិន្នន័យ",
                        paginate: {
                            next: "បន្ទាប់",
                            previous: "មុន"
                        }
                    }
                });

                // ✅ DELETE MODAL
                $(document).on('click', '.deleteBtn', function() {

                    let id = $(this).data('id');
                    let name = $(this).data('name');

                    $('#deptName').text(name);
                    $('#deleteForm').attr('action', '/admin/departments/' + id);

                    $('#deleteModal').modal('show');
                });

            });
        </script>
    @endpush
