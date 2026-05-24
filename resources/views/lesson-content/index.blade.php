@extends('admin.layouts.master')

@section('title', $pageTitle ?? 'មាតិកាមេរៀន')

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1>{{ $pageTitle ?? 'មាតិកាមេរៀន' }}</h1>
                    <p class="text-muted mb-0">{{ $pageDescription ?? 'គ្រប់គ្រងមេរៀន វីដេអូ ឯកសារ កិច្ចការ សំណួរ និងតំណភ្ជាប់ដូច Moodle។' }}</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? 'មាតិកាមេរៀន' }}</li>
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
            <h3 class="card-title">បញ្ជីមាតិកាម៉ូឌុល</h3>
            <div class="card-tools">
                <a href="{{ $createRoute ?? route('admin.lesson-contents.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> បង្កើត
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="lessonContentTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>វគ្គសិក្សា</th>
                            <th>ម៉ូឌុល</th>
                            <th>លំដាប់</th>
                            <th>ចំណងជើង</th>
                            <th>ប្រភេទ</th>
                            <th>ការបង្ហាញ</th>
                            <th>បោះផ្សាយ</th>
                            <th>ពេលអាចមើលបាន</th>
                            <th>ពិន្ទុ</th>
                            <th>សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lessonContents as $key => $lessonContent)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $lessonContent->course->title ?? 'N/A' }}</td>
                                <td>
                                    ម៉ូឌុល {{ $lessonContent->module_number }}
                                    @if ($lessonContent->module_title)
                                        <br><small class="text-muted">{{ $lessonContent->module_title }}</small>
                                    @endif
                                </td>
                                <td>{{ $lessonContent->position }}</td>
                                <td>
                                    <strong>{{ $lessonContent->title }}</strong>
                                    <br><small class="text-muted">{{ $lessonContent->slug }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ [
                                            'lesson' => 'មេរៀន',
                                            'page' => 'ទំព័រ',
                                            'video' => 'វីដេអូ',
                                            'file' => 'ឯកសារ',
                                            'url' => 'តំណភ្ជាប់',
                                            'assignment' => 'កិច្ចការ',
                                            'quiz' => 'សំណួរ',
                                            'forum' => 'វេទិកា',
                                        ][$lessonContent->content_type] ?? $lessonContent->content_type }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $lessonContent->visibility === 'visible' ? 'success' : ($lessonContent->visibility === 'scheduled' ? 'warning' : 'secondary') }}">
                                        {{ [
                                            'visible' => 'បង្ហាញ',
                                            'hidden' => 'លាក់',
                                            'scheduled' => 'កំណត់ពេល',
                                        ][$lessonContent->visibility] ?? $lessonContent->visibility }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $lessonContent->is_published ? 'success' : 'secondary' }}">
                                        {{ $lessonContent->is_published ? 'បាទ/ចាស' : 'ទេ' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $lessonContent->available_from?->format('Y-m-d H:i') ?? 'គ្រប់ពេល' }}
                                    @if ($lessonContent->available_until)
                                        <br><small class="text-muted">ដល់ {{ $lessonContent->available_until->format('Y-m-d H:i') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($lessonContent->max_score)
                                        {{ $lessonContent->passing_score ?? 0 }} / {{ $lessonContent->max_score }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.lesson-contents.edit', $lessonContent->id) }}" class="btn btn-info btn-sm">កែប្រែ</a>
                                    <button class="btn btn-danger btn-sm deleteBtn"
                                        data-id="{{ $lessonContent->id }}"
                                        data-name="{{ $lessonContent->title }}">
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
                        <h5 class="modal-title">លុបមាតិកាមេរៀន</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">តើអ្នកពិតជាចង់លុប <b id="lessonContentName"></b> មែនទេ?</div>
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
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#lessonContentTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                order: [[1, 'asc'], [2, 'asc'], [3, 'asc']],
                columnDefs: [{ targets: -1, orderable: false, searchable: false }]
            });

            $(document).on('click', '.deleteBtn', function() {
                $('#lessonContentName').text($(this).data('name'));
                $('#deleteForm').attr('action', '/admin/lesson-contents/' + $(this).data('id'));
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endpush
