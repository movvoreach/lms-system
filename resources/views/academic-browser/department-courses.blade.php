@extends('admin.layouts.master')

@section('title', 'វគ្គសិក្សានៃនាយកដ្ឋាន')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">{{ $department->department_name }}</h1>

                    <p class="text-muted mb-0">
                        {{ $department->department_code ?? 'គ្មាន' }}

                        @if ($department->faculty)
                            / {{ $department->faculty->faculty_name }}
                        @endif
                    </p>
                </div>

                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.departments.index') }}">
                                នាយកដ្ឋាន
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            វគ្គសិក្សា
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="card shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="row">

                <div class="col-md-3">
                    <span class="text-muted d-block">
                        កូដនាយកដ្ឋាន
                    </span>

                    <strong>
                        {{ $department->department_code ?? 'គ្មាន' }}
                    </strong>
                </div>

                <div class="col-md-3">
                    <span class="text-muted d-block">
                        មហាវិទ្យាល័យ
                    </span>

                    <strong>
                        {{ $department->faculty->faculty_name ?? 'គ្មាន' }}
                    </strong>
                </div>

                <div class="col-md-3">
                    <span class="text-muted d-block">
                        ព្រឹទ្ធបុរស
                    </span>

                    <strong>
                        {{ $department->deans ?? 'គ្មាន' }}
                    </strong>
                </div>

                <div class="col-md-3">
                    <span class="text-muted d-block">
                        ចំនួនវគ្គសិក្សា
                    </span>

                    <strong>
                        {{ $department->courses->count() }}
                    </strong>
                </div>

            </div>
        </div>
    </div>

    <div class="card shadow-sm">

        <div class="card-header d-flex align-items-center">

            <h3 class="card-title mb-0">
                វគ្គសិក្សាក្នុងនាយកដ្ឋាន
            </h3>

            <div class="card-tools ml-auto">

                <a href="{{ route('admin.courses.create') }}"
                    class="btn btn-primary btn-sm">

                    <i class="fas fa-plus mr-1"></i>
                    បន្ថែមវគ្គសិក្សា
                </a>

                <a href="{{ route('admin.departments.index') }}"
                    class="btn btn-light border btn-sm">

                    <i class="fas fa-arrow-left mr-1"></i>
                    ត្រឡប់ក្រោយ
                </a>

            </div>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table id="departmentCoursesTable"
                    class="table table-bordered table-striped w-100">

                    <thead>
                        <tr>
                            <th>ល.រ</th>
                            <th>កូដ</th>
                            <th>វគ្គសិក្សា</th>
                            <th>ប្រភេទ</th>
                            <th>ឆមាស</th>
                            <th>សិស្ស</th>
                            <th>ម៉ោងសិក្សា</th>
                            <th>ថ្ងៃចាប់ផ្តើម</th>
                            <th>ថ្ងៃបញ្ចប់</th>
                            <th>ស្ថានភាព</th>
                            <th>សកម្មភាព</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($department->courses as $key => $course)

                            <tr>

                                <td>{{ $key + 1 }}</td>

                                <td>
                                    {{ $course->code ?? 'គ្មាន' }}
                                </td>

                                <td>
                                    {{ $course->title }}

                                    <br>

                                    <small class="text-muted">
                                        {{ \Illuminate\Support\Str::limit($course->description, 80) }}
                                    </small>
                                </td>

                                <td>
                                    {{ $course->category->name ?? 'គ្មាន' }}
                                </td>

                                <td>

                                    {{ $course->semester->semester_name ?? 'គ្មាន' }}

                                    @if ($course->semester?->academicYear)

                                        <br>

                                        <small class="text-muted">
                                            {{ $course->semester->academicYear->year_label }}
                                        </small>

                                    @endif

                                </td>

                                <td>
                                    {{ $course->studentRegistrations->count() }}
                                </td>

                                <td>
                                    {{ $course->duration_hours ?? 'គ្មាន' }}
                                </td>

                                <td>
                                    {{ $course->start_date?->format('Y-m-d') ?? 'គ្មាន' }}
                                </td>

                                <td>
                                    {{ $course->end_date?->format('Y-m-d') ?? 'គ្មាន' }}
                                </td>

                                <td>

                                    <span class="badge badge-{{ $course->is_active ? 'success' : 'secondary' }}">

                                        {{ $course->is_active ? 'បើក' : 'បិទ' }}

                                    </span>

                                </td>

                                <td>

                                    <div class="dropdown">

                                        <button type="button"
                                            class="btn btn-primary btn-sm"
                                            data-toggle="dropdown">



                                            <i class="fas fa-ellipsis-v ml-1"></i>

                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right">

                                            <a href="{{ route('admin.courses.students.index', $course->id) }}"
                                                class="dropdown-item">

                                                <i class="fas fa-eye mr-2"></i>

                                                មើលសិស្ស

                                            </a>

                                            <a href="{{ route('admin.courses.edit', $course->id) }}"
                                                class="dropdown-item">

                                                <i class="fas fa-edit mr-2"></i>

                                                កែប្រែ

                                            </a>

                                            <a href="{{ route('admin.certificate-requests.index', ['course_id' => $course->id]) }}"
                                                class="dropdown-item">

                                                <i class="fas fa-file-alt mr-2"></i>

                                                សំណើវិញ្ញាបនបត្រ

                                            </a>

                                            <button type="button"
                                                class="dropdown-item text-danger deleteCourseBtn"
                                                data-id="{{ $course->id }}"
                                                data-name="{{ $course->title }}">

                                                <i class="fas fa-trash-alt mr-2"></i>

                                                លុប

                                            </button>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="11"
                                    class="text-center text-muted py-4">

                                    មិនមានវគ្គសិក្សាសម្រាប់នាយកដ្ឋាននេះទេ។

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="modal fade" id="deleteCourseModal">

        <div class="modal-dialog">

            <form method="POST" id="deleteCourseForm">

                @csrf
                @method('DELETE')

                <div class="modal-content">

                    <div class="modal-header bg-danger text-white">

                        <h5 class="modal-title">
                            លុបវគ្គសិក្សា
                        </h5>

                        <button type="button"
                            class="close text-white"
                            data-dismiss="modal">

                            &times;

                        </button>

                    </div>

                    <div class="modal-body">

                        តើអ្នកពិតជាចង់លុប

                        <b id="courseName"></b>

                        មែនទេ?

                    </div>

                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

                            បោះបង់

                        </button>

                        <button type="submit"
                            class="btn btn-danger">

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

        $(function() {

            $('#departmentCoursesTable').DataTable({

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

            $(document).on('click', '.deleteCourseBtn', function() {

                $('#courseName').text($(this).data('name'));

                $('#deleteCourseForm').attr(
                    'action',
                    '/admin/courses/' + $(this).data('id')
                );

                $('#deleteCourseModal').modal('show');

            });

        });

    </script>

@endpush
