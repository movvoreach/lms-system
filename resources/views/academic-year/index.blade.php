@extends('admin.layouts.master')

@section('title', 'ឆ្នាំសិក្សា')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">ឆ្នាំសិក្សា</h1>
                    <p class="text-muted mb-0">បង្កើត កែប្រែ និងគ្រប់គ្រងឆ្នាំសិក្សារបស់សាលា។</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item active">ឆ្នាំសិក្សា</li>
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
            <h3 class="card-title">បញ្ជីឆ្នាំសិក្សា</h3>

            <div class="card-tools">
                <a href="{{ route('admin.academic-years.create') }}" class="btn btn-primary btn-sm">
                    បង្កើតថ្មី
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="academicYearTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ឈ្មោះឆ្នាំសិក្សា</th>
                            <th>ថ្ងៃចាប់ផ្តើម</th>
                            <th>ថ្ងៃបញ្ចប់</th>
                            <th>ស្ថានភាព</th>
                            <th>មធ្យមសិក្សា</th>
                            <th>កំណត់ត្រាសិស្ស</th>
                            <th>សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($academicYears as $key => $academicYear)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <span class="badge badge-primary">
                                        {{ $academicYear->year_label }}
                                    </span>
                                </td>
                                <td>{{ $academicYear->start_date?->format('Y-m-d') }}</td>
                                <td>{{ $academicYear->end_date?->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ ucfirst($academicYear->status) }}
                                    </span>
                                </td>
                                <td>{{ $academicYear->semesters_count }}</td>
                                <td>
                                    <a href="{{ route('admin.academic-progression.show', $academicYear->academic_year_id) }}">
                                        {{ $academicYear->student_records_count }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.academic-years.edit', $academicYear->academic_year_id) }}"
                                        class="btn btn-info btn-sm">
                                        កែប្រែ
                                    </a>

                                    <button class="btn btn-danger btn-sm deleteBtn"
                                        data-id="{{ $academicYear->academic_year_id }}"
                                        data-name="{{ $academicYear->year_label }}">
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
                        <h5 class="modal-title">លុបឆ្នាំសិក្សា</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        តើអ្នកពិតជាចង់លុប <b id="academicYearName"></b> មែនទេ?
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
            $('#academicYearTable').DataTable({
                responsive: false,
                scrollX: true,
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
