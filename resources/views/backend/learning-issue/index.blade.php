@extends('admin.layouts.master')

@section('title', 'Learning Issues')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">Learning Issues</h1>
                    <p class="text-muted mb-0">Student difficulty reports, assignment problems, deadlines, and technical issues.</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Learning Issues</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Reports</h3>
            <div class="card-tools">
                @can('learning_issues.analytics')
                    <a href="{{ route('admin.learning-issues.analytics') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-chart-bar"></i> Analytics
                    </a>
                @endcan
                @can('learning_issues.create')
                    <a href="{{ route('admin.learning-issues.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Report Issue
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 mb-2">
                    <select id="statusFilter" class="form-control">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select id="typeFilter" class="form-control">
                        <option value="">All issue types</option>
                        @foreach ($issueTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select id="priorityFilter" class="form-control">
                        <option value="">All priorities</option>
                        @foreach ($priorities as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table id="learningIssueTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Lesson</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const table = $('#learningIssueTable').DataTable({
                ajax: {
                    url: '{{ route('admin.learning-issues.data') }}',
                    data: function (params) {
                        params.status = $('#statusFilter').val();
                        params.issue_type = $('#typeFilter').val();
                        params.priority = $('#priorityFilter').val();
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'student' },
                    { data: 'course' },
                    { data: 'lesson' },
                    { data: 'title' },
                    { data: 'type' },
                    { data: 'priority' },
                    { data: 'status' },
                    { data: 'progress' },
                    { data: 'created_at' },
                    { data: 'action' },
                ],
                order: [[0, 'desc']],
                columnDefs: [{ targets: [6, 7, 8, 10], orderable: false, searchable: false }]
            });

            $('#statusFilter, #typeFilter, #priorityFilter').on('change', function () {
                table.ajax.reload();
            });
        });
    </script>
@endpush
