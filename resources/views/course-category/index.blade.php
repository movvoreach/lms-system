@extends('admin.layouts.master')

@section('title', 'គ្រប់គ្រងប្រភេទ Course')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">គ្រប់គ្រងប្រភេទ Course</h1>
                    <p class="text-muted mb-0">បង្កើត ពិនិត្យ កែប្រែ និងលុបប្រភេទ Course។</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item active">ប្រភេទ Course</li>
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
            <h3 class="card-title">បញ្ជីប្រភេទ Course</h3>
            <div class="card-tools">
                <a href="{{ route('admin.course-categories.create') }}" class="btn btn-primary btn-sm">បង្កើត</a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="courseCategoryTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ឈ្មោះ</th>
                            <th>ពិពណ៌នា</th>
                            <th>Course</th>
                            <th>ស្ថានភាព</th>
                            <th>សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courseCategories as $key => $courseCategory)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $courseCategory->name }}</td>
                                <td>{{ $courseCategory->description ?? 'N/A' }}</td>
                                <td>{{ $courseCategory->courses_count }}</td>
                                <td>
                                    <span class="badge badge-{{ $courseCategory->is_active ? 'success' : 'secondary' }}">
                                        {{ $courseCategory->is_active ? 'សកម្ម' : 'មិនសកម្ម' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.course-categories.edit', $courseCategory->id) }}"
                                        class="btn btn-info btn-sm">កែប្រែ</a>
                                    <button class="btn btn-danger btn-sm deleteBtn"
                                        data-id="{{ $courseCategory->id }}"
                                        data-name="{{ $courseCategory->name }}">
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
                        <h5 class="modal-title">លុបប្រភេទ Course</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">តើអ្នកប្រាកដថាចង់លុប <b id="courseCategoryName"></b> ឬ?</div>
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
            $('#courseCategoryTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [{ targets: -1, orderable: false, searchable: false }]
            });

            $(document).on('click', '.deleteBtn', function() {
                $('#courseCategoryName').text($(this).data('name'));
                $('#deleteForm').attr('action', '/admin/course-categories/' + $(this).data('id'));
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endpush
