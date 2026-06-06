@extends('admin.layouts.master')

@section('title', 'គ្រប់គ្រងមហាវិទ្យាល័យ')

@push('styles')

    <style>
        /* ===== TABLE FIX ===== */
        #facultyTable {
            width: 100% !important;
            table-layout: fixed;
        }

        #facultyTable td {
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
                        <h1 class="mb-1">គ្រប់គ្រងមហាវិទ្យាល័យ</h1>
                        <p class="text-muted mb-0">បង្កើត ពិនិត្យ កែប្រែ និងលុបមហាវិទ្យាល័យ។</p>
                    </div>
                    <div class="col-sm-5">
                        <ol class="breadcrumb float-sm-right mb-0">
                            <li class="breadcrumb-item">
                                <a href="">

                                    ផ្ទាំងគ្រប់គ្រង
                                </a>
                            </li>
                            <li class="breadcrumb-item active">មហាវិទ្យាល័យ</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <div class="card shadow-sm mt-2">

            <div class="card-header">
                <h3 class="card-title">បញ្ជីមហាវិទ្យាល័យ</h3>

                <div class="card-tools">
                    <a href="{{ route('admin.faculty.create') }}" class="btn btn-primary btn-sm">
                        បង្កើត
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- ✅ RESPONSIVE WRAPPER --}}
                <div class="table-responsive">

                    <table id="facultyTable" class="table table-bordered table-striped w-100">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>កូដមហាវិទ្យាល័យ</th>
                                <th>ឈ្មោះមហាវិទ្យាល័យ</th>
                                <th>សកម្មភាព</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($faculties as $key => $faculty)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>{{ $faculty->faculty_code ?? 'N/A' }}</td>
                                    <td>{{ $faculty->faculty_name ?? 'N/A' }}</td>


                                    <td>
                                        <a href="{{ route('admin.faculty.edit', $faculty->faculty_id) }}"
                                            class="btn btn-info btn-sm">
                                            Edit
                                        </a>

                                        <button type="button" class="btn btn-danger btn-sm deleteBtn"
                                            data-id="{{ $faculty->faculty_id }}"
                                            data-name="{{ $faculty->faculty_name }}"
                                            data-delete-url="{{ route('admin.faculty.destroy', $faculty->faculty_id) }}">
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
                            <h5 class="modal-title">Delete Faculty</h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                &times;
                            </button>
                        </div>

                        <div class="modal-body">
                            Are you sure delete <b id="facultyName"></b>?
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

        <script>
            $(document).ready(function() {

                // ✅ FIXED DATATABLE (SEARCH + RESPONSIVE + WIDTH)
                $('#facultyTable').DataTable({
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

                    $('#facultyName').text(name);
                    $('#deleteForm').attr('action', '/admin/faculty/' + id);

                    $('#deleteModal').modal('show');
                });

            });
        </script>
    @endpush
