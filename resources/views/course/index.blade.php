@extends('admin.layouts.master')

@section('title', 'គ្រប់គ្រង Course')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">គ្រប់គ្រង Course</h1>
                    <p class="text-muted mb-0">បង្កើត ពិនិត្យ កែប្រែ និងលុប Course តាមប្រភេទ និងដេប៉ាតឺម៉ង់។</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item active">Course</li>
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
            <h3 class="card-title">បញ្ជី Course</h3>
            <div class="card-tools">
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary btn-sm">បង្កើត</a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="courseTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>ចំណងជើង</th>
                            <th>ប្រភេទ</th>
                            <th>ដេប៉ាតឺម៉ង់</th>
                            <th>Semester</th>
                            <th>ម៉ោង</th>
                            <th>តម្លៃ</th>
                            <th>ចាប់ផ្តើម</th>
                            <th>បញ្ចប់</th>
                            <th>ស្ថានភាព</th>
                            <th>សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courses as $key => $course)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $course->code ?? 'N/A' }}</td>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->category->name ?? 'N/A' }}</td>
                                <td>{{ $course->department->department_name ?? 'N/A' }}</td>
                                <td>{{ $course->semester ? $course->semester->semester_name . ' - ' . ($course->semester->academicYear->year_label ?? 'N/A') : 'N/A' }}</td>
                                <td>{{ $course->duration_hours ?? 'N/A' }}</td>
                                <td>{{ number_format((float) $course->fee, 2) }}</td>
                                <td>{{ $course->start_date?->format('Y-m-d') ?? 'N/A' }}</td>
                                <td>{{ $course->end_date?->format('Y-m-d') ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $course->is_active ? 'success' : 'secondary' }}">
                                        {{ $course->is_active ? 'សកម្ម' : 'មិនសកម្ម' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-info btn-sm">កែប្រែ</a>
                                    <button class="btn btn-danger btn-sm deleteBtn"
                                        data-id="{{ $course->id }}"
                                        data-name="{{ $course->title }}">
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
                        <h5 class="modal-title">លុប Course</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">តើអ្នកប្រាកដថាចង់លុប <b id="courseName"></b> ឬ?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">បោះបង់</button>
                        <button type="submit" class="btn btn-danger">លុប</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#courseTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [{ targets: -1, orderable: false, searchable: false }]
            });

            $(document).on('click', '.deleteBtn', function() {
                $('#courseName').text($(this).data('name'));
                $('#deleteForm').attr('action', '/admin/courses/' + $(this).data('id'));
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endpush
