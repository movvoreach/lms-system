@extends('admin.layouts.master')

@section('title', 'គ្រប់គ្រងអ្នកប្រើ')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">គ្រប់គ្រងអ្នកប្រើ</h1>
                    <p class="text-muted mb-0">បង្កើត ពិនិត្យ កែប្រែ និងកំណត់តួនាទីអ្នកប្រើ។</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item active">អ្នកប្រើ</li>
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
            <h3 class="card-title">បញ្ជីអ្នកប្រើ</h3>
            <div class="card-tools">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">បង្កើត</a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="userTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ឈ្មោះអ្នកប្រើ</th>
                            <th>Email</th>
                            <th>តួនាទី</th>
                            <th>ស្ថានភាព</th>
                            <th>ចូលចុងក្រោយ</th>
                            <th>សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse ($user->roles as $role)
                                        <span class="badge badge-primary">{{ $role->role_name }}</span>
                                    @empty
                                        <span class="text-muted">គ្មានតួនាទី</span>
                                    @endforelse
                                </td>
                                <td>
                                    <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">
                                        {{ $user->is_active ? 'សកម្ម' : 'មិនសកម្ម' }}
                                    </span>
                                </td>
                                <td>{{ $user->last_login_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-info btn-sm">កែប្រែ</a>
                                    <button class="btn btn-danger btn-sm deleteBtn"
                                        data-id="{{ $user->user_id }}"
                                        data-name="{{ $user->username }}">
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
                        <h5 class="modal-title">លុបអ្នកប្រើ</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">តើអ្នកប្រាកដថាចង់លុប <b id="userName"></b> ឬ?</div>
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
            $('#userTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [{ targets: -1, orderable: false, searchable: false }]
            });

            $(document).on('click', '.deleteBtn', function() {
                $('#userName').text($(this).data('name'));
                $('#deleteForm').attr('action', '/admin/users/' + $(this).data('id'));
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endpush
