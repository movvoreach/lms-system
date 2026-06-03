@extends('admin.layouts.master')

@section('title', 'ឆមាស')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">ឆមាស</h1>
                    <p class="text-muted mb-0">គ្រប់គ្រងឆមាសក្នុងឆ្នាំសិក្សានីមួយៗ។</p>
                </div>

                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item active">ឆមាស</li>
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
            <h3 class="card-title">បញ្ជីឆមាស</h3>

            <div class="card-tools">
                <a href="{{ route('admin.semesters.create') }}" class="btn btn-primary btn-sm">
                    បង្កើតថ្មី
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="semesterTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ឆ្នាំសិក្សា</th>
                            <th>ឈ្មោះឆមាស</th>
                            <th>ថ្ងៃចាប់ផ្តើម</th>
                            <th>ថ្ងៃបញ្ចប់</th>
                            <th>សកម្មភាព</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($semesters as $key => $semester)
                            <tr>
                                <td>{{ $key + 1 }}</td>

                                <td>{{ $semester->academicYear->year_label ?? 'មិនមាន' }}</td>

                                <td>
                                    <span class="badge badge-primary">
                                        {{ $semester->semester_name }}
                                    </span>
                                </td>

                                <td>{{ $semester->start_date?->format('Y-m-d') }}</td>

                                <td>{{ $semester->end_date?->format('Y-m-d') }}</td>

                                <td>
                                    <a href="{{ route('admin.semesters.edit', $semester->semester_id) }}"
                                        class="btn btn-info btn-sm">
                                        កែប្រែ
                                    </a>

                                    <button class="btn btn-danger btn-sm deleteBtn"
                                        data-id="{{ $semester->semester_id }}"
                                        data-name="{{ $semester->semester_name }}">
                                        លុប
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
                        <h5 class="modal-title">លុបឆមាស</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        តើអ្នកពិតជាចង់លុប <b id="semesterName"></b> មែនទេ?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            បោះបង់
                        </button>
                        <button type="submit" class="btn btn-danger">
                            លុប
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
