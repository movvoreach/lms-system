@extends('admin.layouts.master')

@section('title', 'វឌ្ឍនភាពឆ្នាំសិក្សា')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">វឌ្ឍនភាពឆ្នាំសិក្សា</h1>
                    <p class="text-muted mb-0">ទិន្នន័យឆ្នាំសិក្សាដែលបានរក្សាទុកអចិន្ត្រៃយ៍សម្រាប់កំណត់ត្រាសិស្ស។</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item active">វឌ្ឍនភាពឆ្នាំសិក្សា</li>
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

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">ប័ណ្ណសារឆ្នាំសិក្សា</h3>

            <div class="card-tools">
                <a href="{{ route('admin.academic-progression.promote') }}" class="btn btn-primary btn-sm">
                    ផ្ទេរសិស្សឡើងឆ្នាំ
                </a>
            </div>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ឆ្នាំសិក្សា</th>
                        <th>កាលបរិច្ឆេទ</th>
                        <th>ស្ថានភាព</th>
                        <th>កំណត់ត្រាសិស្ស</th>
                        <th>មើលប័ណ្ណសារ</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($academicYears as $year)
                        <tr>
                            <td>{{ $year->year_label }}</td>

                            <td>
                                {{ $year->start_date?->format('Y-m-d') }}
                                -
                                {{ $year->end_date?->format('Y-m-d') }}
                            </td>

                            <td>
                                <span class="badge badge-secondary">
                                    {{ ucfirst($year->status) }}
                                </span>
                            </td>

                            <td>{{ $year->student_records_count }}</td>

                            <td>
                                <a href="{{ route('admin.academic-progression.show', $year->academic_year_id) }}"
                                    class="btn btn-info btn-sm">
                                    មើលប្រវត្តិ
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                មិនមានទិន្នន័យឆ្នាំសិក្សា
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
