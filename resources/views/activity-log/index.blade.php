@extends('admin.layouts.master')

@section('title', 'Activity Logs')

@section('content')
<section class="content mt-3">
    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">កំណត់ត្រាសកម្មភាព</h1>
                <p class="text-muted mb-0">តាមដាន Login, Create, Update, Delete, Upload, Download និងការផ្លាស់ប្តូរតួនាទី។</p>
            </div>

            <div class="btn-group">
                <a href="{{ route('admin.activity-logs.export.excel') }}" class="btn btn-success js-export">
                    <i class="fas fa-file-excel mr-1"></i> Excel
                </a>
                <a href="{{ route('admin.activity-logs.export.pdf') }}" class="btn btn-danger js-export" target="_blank">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <form id="activityFilter" class="row">
                    <div class="col-md-3 mb-2">
                        <label class="small text-muted mb-1">សកម្មភាព</label>
                        <select name="action" class="form-control">
                            <option value="">ទាំងអស់</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}">{{ str($action)->replace('_', ' ')->title() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="small text-muted mb-1">ម៉ូឌុល</label>
                        <select name="module" class="form-control">
                            <option value="">ទាំងអស់</option>
                            @foreach($modules as $module)
                                <option value="{{ $module }}">{{ $module }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1">ចាប់ពី</label>
                        <input type="date" name="date_from" class="form-control">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1">ដល់</label>
                        <input type="date" name="date_to" class="form-control">
                    </div>
                    <div class="col-md-2 mb-2 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-block" id="applyFilter">
                            <i class="fas fa-filter mr-1"></i> ច្រោះ
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="activityLogsTable" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th>កាលបរិច្ឆេទ</th>
                                <th>អ្នកប្រើ</th>
                                <th>សកម្មភាព</th>
                                <th>ម៉ូឌុល</th>
                                <th>ពិពណ៌នា</th>
                                <th>IP</th>
                                <th>ឧបករណ៍</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .badge-purple {
        background: #6f42c1;
        color: #fff;
    }
</style>
@endpush

@push('scripts')
<script>
    $(function () {
        const table = $('#activityLogsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('admin.activity-logs.data') }}',
                data: function (data) {
                    $('#activityFilter').serializeArray().forEach(function (field) {
                        data[field.name] = field.value;
                    });
                }
            },
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'user', name: 'user' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'module', name: 'module' },
                { data: 'description', name: 'description' },
                { data: 'ip_address', name: 'ip_address' },
                { data: 'user_agent', name: 'user_agent', orderable: false }
            ],
            order: [[0, 'desc']]
        });

        $('#applyFilter').on('click', function () {
            table.ajax.reload();
        });

        $('.js-export').on('click', function () {
            const query = $('#activityFilter').serialize();
            const separator = this.href.includes('?') ? '&' : '?';
            this.href = this.href.split('?')[0] + (query ? separator + query : '');
        });
    });
</script>
@endpush
