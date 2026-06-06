@extends('admin.layouts.master')

@section('title', 'Certificate Requests')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">Certificate Requests</h1>
                    <p class="text-muted mb-0">
                        @if ($course)
                            Course: {{ $course->title }}
                        @else
                            Approve or reject teacher certificate requests.
                        @endif
                    </p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Certificates</li>
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
            <h3 class="card-title">Requests</h3>
        </div>
        <div class="card-body table-responsive">
            <table id="certificateRequestTable" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($certificateRequests as $key => $requestItem)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                {{ $requestItem->student->student_number ?? 'N/A' }}
                                <br><small>{{ $requestItem->student->user->username ?? 'N/A' }}</small>
                            </td>
                            <td>{{ $requestItem->course->title ?? 'N/A' }}</td>
                            <td>{{ $requestItem->requestedByTeacher->user->username ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $requestItem->status === 'approved' ? 'success' : ($requestItem->status === 'rejected' ? 'danger' : 'info') }}">
                                    {{ ucfirst($requestItem->status) }}
                                </span>
                            </td>
                            <td>{{ $requestItem->requested_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                            <td>
                                @if ($requestItem->status === 'pending')
                                    <form method="POST" action="{{ route('admin.certificate-requests.update', $requestItem->certificate_request_id) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.certificate-requests.update', $requestItem->certificate_request_id) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <input type="hidden" name="admin_note" value="Rejected by admin">
                                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                @else
                                    {{ $requestItem->reviewed_at?->format('Y-m-d H:i') ?? 'Reviewed' }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#certificateRequestTable').DataTable({
                order: [[0, 'asc']],
                columnDefs: [{ targets: -1, orderable: false, searchable: false }]
            });
        });
    </script>
@endpush
