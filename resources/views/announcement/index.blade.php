@extends('admin.layouts.master')

@section('title', 'Announcements')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">Announcements</h1>
                    <p class="text-muted mb-0">Manage Moodle-style LMS announcements and notification delivery.</p>
                </div>
                <div class="col-sm-5 text-sm-right">
                    @can('announcements.manage')
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
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
                    <select id="priorityFilter" class="form-control">
                        <option value="">All priorities</option>
                        @foreach ($priorities as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select id="targetFilter" class="form-control">
                        <option value="">All targets</option>
                        @foreach ($targets as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="announcementTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Target</th>
                            <th>Course</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Creator</th>
                            <th>Created</th>
                            <th>Publish</th>
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
        $(document).ready(function () {
            const table = $('#announcementTable').DataTable({
                ajax: {
                    url: '{{ route('admin.announcements.data') }}',
                    data: function (params) {
                        params.status = $('#statusFilter').val();
                        params.priority = $('#priorityFilter').val();
                        params.target_type = $('#targetFilter').val();
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'title' },
                    { data: 'target' },
                    { data: 'course' },
                    { data: 'priority' },
                    { data: 'status' },
                    { data: 'creator' },
                    { data: 'created_at' },
                    { data: 'publish_at' },
                    { data: 'action' },
                ],
                order: [[0, 'desc']],
                columnDefs: [{ targets: [4, 5, 9], orderable: false, searchable: false }]
            });

            $('#statusFilter, #priorityFilter, #targetFilter').on('change', function () {
                table.ajax.reload();
            });
        });
    </script>
@endpush
