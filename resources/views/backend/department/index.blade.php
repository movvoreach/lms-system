@extends('admin.layouts.master')

@section('title', 'នាយកដ្ឋាន')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">នាយកដ្ឋាន</h1>
                    <p class="text-muted mb-0">មើលនាយកដ្ឋាន ចំនួនវគ្គសិក្សា និងវគ្គសិក្សាដែលពាក់ព័ន្ធ។</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">ផ្ទាំងគ្រប់គ្រង</a>
                        </li>
                        <li class="breadcrumb-item active">នាយកដ្ឋាន</li>
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

    <div class="card shadow-sm mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.departments.index') }}" class="row align-items-end">
                <div class="col-md-4">
                    <label>ឆ្នាំ</label>
                    <select name="year" class="form-control" onchange="this.form.submit()">
                        @forelse ($years as $year)
                            <option value="{{ $year }}" @selected((int) $selectedYear === (int) $year)>
                                {{ $year }}
                            </option>
                        @empty
                            <option value="{{ $selectedYear }}">{{ $selectedYear }}</option>
                        @endforelse
                    </select>
                </div>

                <div class="col-md-8 text-md-right mt-3 mt-md-0">
                    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> បន្ថែមនាយកដ្ឋាន
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">បញ្ជីនាយកដ្ឋាន</h3>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="departmentTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>ល.រ</th>
                            <th>កូដនាយកដ្ឋាន</th>
                            <th>ឈ្មោះនាយកដ្ឋាន</th>
                            <th>មហាវិទ្យាល័យ</th>
                            <th>ព្រឹទ្ធបុរស</th>
                            <th>វគ្គសិក្សា</th>
                            <th>ឆ្នាំ</th>
                            <th>បង្កើតនៅ</th>
                            <th>សកម្មភាព</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($departments as $key => $department)
                            <tr>
                                <td>{{ $key + 1 }}</td>

                                <td>
                                    <span class="badge badge-primary">
                                        {{ $department->department_code ?? 'គ្មាន' }}
                                    </span>
                                </td>

                                <td>{{ $department->department_name }}</td>

                                <td>{{ $department->faculty->faculty_name ?? 'គ្មាន' }}</td>

                                <td>{{ $department->deans ?? 'គ្មាន' }}</td>

                                <td>
                                    <span class="badge badge-info">
                                        {{ $department->courses_count ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    {{ $department->created_at?->format('Y') ?? $selectedYear }}
                                </td>

                                <td>
                                    {{ $department->created_at?->format('Y-m-d H:i') ?? 'គ្មាន' }}
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary btn-sm text-center"
                                            data-toggle="dropdown">

                                            <i class="fas fa-ellipsis-v ml-1"></i>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('admin.departments.courses.index', $department->department_id) }}"
                                                class="dropdown-item">
                                                <i class="fas fa-eye mr-2"></i>
                                                មើលវគ្គសិក្សា
                                            </a>

                                            <a href="{{ route('admin.departments.edit', $department->department_id) }}"
                                                class="dropdown-item">
                                                <i class="fas fa-edit mr-2"></i>
                                                កែប្រែ
                                            </a>

                                            <button type="button"
                                                class="dropdown-item text-danger deleteBtn"
                                                data-id="{{ $department->department_id }}"
                                                data-name="{{ $department->department_name }}"
                                                data-delete-url="{{ route('admin.departments.destroy', $department->department_id) }}">
                                                <i class="fas fa-trash-alt mr-2"></i>
                                                លុប
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if ($departments->isEmpty() && false)
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    មិនមានទិន្នន័យនាយកដ្ឋានទេ។
                                </td>
                            </tr>
                        @endif
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
                        <h5 class="modal-title">លុបនាយកដ្ឋាន</h5>

                        <button type="button" class="close text-white"
                            data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        តើអ្នកពិតជាចង់លុប
                        <b id="deptName"></b>
                        មែនទេ?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">
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
                    lengthMenu: "បង្ហាញ _MENU_ ជួរ",
                    info: "បង្ហាញ _START_ ដល់ _END_ នៃ _TOTAL_ ជួរ",
                    paginate: {
                        first: "ដំបូង",
                        last: "ចុងក្រោយ",
                        next: "បន្ទាប់",
                        previous: "មុន"
                    },
                    zeroRecords: "មិនមានទិន្នន័យ",
                    infoEmpty: "គ្មានទិន្នន័យ",
                    infoFiltered: "(ចម្រោះពី _MAX_ ជួរ)"
                }
            });

            $(document).on('click', '.deleteBtn', function() {

                $('#deptName').text($(this).data('name'));

                $('#deleteForm').attr(
                    'action',
                    '/admin/departments/' + $(this).data('id')
                );

                $('#deleteModal').modal('show');
            });

        });
    </script>
@endpush
