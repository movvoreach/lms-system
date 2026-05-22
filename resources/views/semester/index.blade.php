@extends('admin.layouts.master')

@section('title', 'Semesters')

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">Semesters</h1>
                    <p class="text-muted mb-0">Manage semesters inside each academic year.</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Semesters</li>
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
            <h3 class="card-title">Semester List</h3>

            <div class="card-tools">
                <a href="{{ route('admin.semesters.create') }}" class="btn btn-primary btn-sm">
                    Create
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="semesterTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Academic Year</th>
                            <th>Semester Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($semesters as $key => $semester)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $semester->academicYear->year_label ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-primary">{{ $semester->semester_name }}</span>
                                </td>
                                <td>{{ $semester->start_date?->format('Y-m-d') }}</td>
                                <td>{{ $semester->end_date?->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.semesters.edit', $semester->semester_id) }}"
                                        class="btn btn-info btn-sm">
                                        Edit
                                    </a>

                                    <button class="btn btn-danger btn-sm deleteBtn"
                                        data-id="{{ $semester->semester_id }}"
                                        data-name="{{ $semester->semester_name }}">
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
                        <h5 class="modal-title">Delete Semester</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to delete <b id="semesterName"></b>?
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
            $('#semesterTable').DataTable({
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

                $('#semesterName').text(name);
                $('#deleteForm').attr('action', '/admin/semesters/' + id);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endpush
