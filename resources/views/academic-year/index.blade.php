@extends('admin.layouts.master')

@section('title', 'Academic Years')

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">Academic Years</h1>
                    <p class="text-muted mb-0">Create, update, and manage school academic year ranges.</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Academic Years</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm mt-2">
        <div class="card-header">
            <h3 class="card-title">Academic Year List</h3>

            <div class="card-tools">
                <a href="{{ route('admin.academic-years.create') }}" class="btn btn-primary btn-sm">
                    Create
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="academicYearTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Year Label</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Semesters</th>
                            <th>Student Records</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($academicYears as $key => $academicYear)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <span class="badge badge-primary">{{ $academicYear->year_label }}</span>
                                </td>
                                <td>{{ $academicYear->start_date?->format('Y-m-d') }}</td>
                                <td>{{ $academicYear->end_date?->format('Y-m-d') }}</td>
                                <td><span class="badge badge-secondary">{{ ucfirst($academicYear->status) }}</span></td>
                                <td>{{ $academicYear->semesters_count }}</td>
                                <td>
                                    <a href="{{ route('admin.academic-progression.show', $academicYear->academic_year_id) }}">
                                        {{ $academicYear->student_records_count }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.academic-years.edit', $academicYear->academic_year_id) }}"
                                        class="btn btn-info btn-sm">
                                        Edit
                                    </a>

                                    <button class="btn btn-danger btn-sm deleteBtn"
                                        data-id="{{ $academicYear->academic_year_id }}"
                                        data-name="{{ $academicYear->year_label }}">
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

    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')

                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Delete Academic Year</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to delete <b id="academicYearName"></b>?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
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
            $('#academicYearTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [{
                    targets: -1,
                    orderable: false,
                    searchable: false
                }]
            });

            $(document).on('click', '.deleteBtn', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');

                $('#academicYearName').text(name);
                $('#deleteForm').attr('action', '/admin/academic-years/' + id);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endpush
