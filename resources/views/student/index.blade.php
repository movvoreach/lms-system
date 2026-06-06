@extends('admin.layouts.master')

@section('title', 'សិស្ស')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">សិស្ស</h1>
                    <p class="text-muted mb-0">បង្កើត មើល កែប្រែ និងលុបទិន្នន័យសិស្ស</p>
                </div>

                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item active">សិស្ស</li>
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
            <h3 class="card-title">បញ្ជីសិស្ស</h3>

            <div class="card-tools">
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm">
                    បង្កើតថ្មី
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="studentTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>លេខសម្គាល់សិស្ស</th>
                            <th>ឈ្មោះ</th>
                            <th>អ្នកប្រើប្រាស់</th>
                            <th>មុខវិជ្ជា</th>
                            <th>ភេទ</th>
                            <th>លេខទូរស័ព្ទ</th>
                            <th>ស្ថានភាព</th>
                            <th>សកម្មភាព</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($students as $key => $student)
                            <tr>
                                <td>{{ $key + 1 }}</td>

                                <td>{{ $student->student_number }}</td>

                                <td>
                                    {{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: 'មិនមាន' }}
                                </td>

                                <td>{{ $student->user->username ?? 'មិនមាន' }}</td>

                                <td>{{ $student->course->title ?? 'មិនមាន' }}</td>

                                <td>{{ $student->gender ?? 'មិនមាន' }}</td>

                                <td>{{ $student->phone ?? 'មិនមាន' }}</td>

                                <td>
                                    <span class="badge badge-info">
                                        {{ $student->status ?? 'មិនមាន' }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('admin.students.edit', $student->student_id) }}"
                                        class="btn btn-info btn-sm">
                                        កែប្រែ
                                    </a>

                                    <a href="{{ route('admin.student-enrollments.manage', $student->student_id) }}"
                                        class="btn btn-primary btn-sm">
                                        Enrollment
                                    </a>

                                    <button class="btn btn-danger btn-sm deleteBtn"
                                        data-id="{{ $student->student_id }}"
                                        data-name="{{ $student->student_number }}">
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
                        <h5 class="modal-title">លុបសិស្ស</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        តើអ្នកពិតជាចង់លុប <b id="studentName"></b> មែនទេ?
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
            $('#studentTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [{ targets: -1, orderable: false, searchable: false }]
            });

            $(document).on('click', '.deleteBtn', function() {
                $('#studentName').text($(this).data('name'));
                $('#deleteForm').attr('action', '/admin/students/' + $(this).data('id'));
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endpush
