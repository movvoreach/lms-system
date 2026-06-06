@extends('admin.layouts.master')

@section('title', 'ប្រវត្តិឆ្នាំសិក្សា')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">{{ $academicYear->year_label }} ប្រវត្តិ</h1>
                    <p class="text-muted mb-0">
                        ទិន្នន័យទាំងនេះជាប្រវត្តិសាស្ត្រ ហើយត្រូវរក្សាទុកជាអចិន្ត្រៃយ៍។
                    </p>
                </div>

                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.academic-progression.index') }}">
                                វឌ្ឍនភាពឆ្នាំសិក្សា
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ $academicYear->year_label }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">កំណត់ត្រាសិស្ស</h3>
        </div>

        <div class="card-body table-responsive">
            <table id="academicProgressionRecordsTable" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>លេខសម្គាល់សិស្ស</th>
                        <th>ឈ្មោះ</th>
                        <th>អ្នកប្រើប្រាស់</th>
                        <th>មុខវិជ្ជា</th>
                        <th>ស្ថានភាព</th>
                        <th>ប្រភេទផ្ទេរ</th>
                        <th>ឆ្នាំមុន</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($records as $key => $record)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $record->student->student_number ?? 'មិនមាន' }}</td>

                            <td>
                                {{ trim(($record->student->first_name ?? '') . ' ' . ($record->student->last_name ?? '')) ?: 'មិនមាន' }}
                            </td>

                            <td>{{ $record->student->user->username ?? 'មិនមាន' }}</td>

                            <td>{{ $record->course->title ?? 'មិនមាន' }}</td>

                            <td>
                                <span class="badge badge-info">
                                    {{ $record->status }}
                                </span>
                            </td>

                            <td>{{ $record->promotion_type ?? 'មិនមាន' }}</td>

                            <td>
                                {{ $record->previousRecord->academicYear->year_label ?? 'កំណត់ត្រាដំបូង' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                មិនមានកំណត់ត្រាសិស្សសម្រាប់ឆ្នាំសិក្សានេះ
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#academicProgressionRecordsTable').DataTable({
                order: [[0, 'asc']]
            });
        });
    </script>
@endpush
