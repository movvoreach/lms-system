@extends('admin.layouts.master')

@section('title', 'ផ្ទាំងគ្រប់គ្រង')

@section('content')

<section class="content mt-3">
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="m-0">ផ្ទាំងគ្រប់គ្រង LMS</h1>
                    <small class="text-muted">ទិន្នន័យសង្ខេបពីប្រព័ន្ធសិក្សា</small>
                </div>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">ទំព័រដើម</a>
                    </li>
                    <li class="breadcrumb-item active">ផ្ទាំងគ្រប់គ្រង</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($totalCourses ?? 0) }}</h3>
                        <p>វគ្គសិក្សាសរុប</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <a href="{{ Auth::user()?->can('courses.view') ? route('admin.courses.index') : '#' }}" class="small-box-footer">
                        មើលបន្ថែម <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($totalStudents ?? 0) }}</h3>
                        <p>សិស្សសរុប</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <a href="{{ Auth::user()?->can('students.view') ? route('admin.students.index') : '#' }}" class="small-box-footer">
                        មើលបន្ថែម <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($openLearningIssues ?? 0) }}</h3>
                        <p>បញ្ហាសិក្សាកំពុងបើក</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-life-ring"></i>
                    </div>
                    <a href="{{ Auth::user()?->can('learning_issues.view') ? route('admin.learning-issues.index') : '#' }}" class="small-box-footer">
                        មើលបន្ថែម <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ number_format($totalTeachers ?? 0) }}</h3>
                        <p>គ្រូបង្រៀនសរុប</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <a href="{{ Auth::user()?->can('teachers.view') ? route('admin.teachers.index') : '#' }}" class="small-box-footer">
                        មើលបន្ថែម <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-primary">
                        <i class="fas fa-toggle-on"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">វគ្គសិក្សាសកម្ម</span>
                        <span class="info-box-number">{{ number_format($activeCourses ?? 0) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info">
                        <i class="fas fa-user-plus"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">សិស្សថ្មីថ្ងៃនេះ</span>
                        <span class="info-box-number">{{ number_format($newStudentsToday ?? 0) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-success">
                        <i class="fas fa-book-open"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">មេរៀនសរុប</span>
                        <span class="info-box-number">{{ number_format($totalLessons ?? 0) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-warning">
                        <i class="fas fa-certificate"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">សំណើវិញ្ញាបនបត្ររង់ចាំ</span>
                        <span class="info-box-number">{{ number_format($pendingCertificateRequests ?? 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header border-0 d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-1"></i>
                            សិស្សដែលបានបញ្ចូលថ្មី
                        </h3>

                        @can('students.view')
                            <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                                មើលទាំងអស់
                            </a>
                        @endcan
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>លេខសម្គាល់</th>
                                        <th>ឈ្មោះ</th>
                                        <th>អ៊ីមែល</th>
                                        <th>វគ្គសិក្សា</th>
                                        <th>ស្ថានភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentStudents ?? [] as $student)
                                        <tr>
                                            <td>{{ $student->student_number ?? ('ST-' . $student->student_id) }}</td>
                                            <td>{{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: ($student->user->username ?? '-') }}</td>
                                            <td>{{ $student->user->email ?? '-' }}</td>
                                            <td>{{ $student->course->title ?? '-' }}</td>
                                            <td>
                                                <span class="badge badge-{{ ($student->status ?? '') === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ($student->status ?? '') === 'active' ? 'សកម្ម' : 'មិនសកម្ម' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">មិនទាន់មានទិន្នន័យសិស្ស</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-1"></i>
                            និន្នាការចុះឈ្មោះសិស្ស
                        </h3>
                    </div>

                    <div class="card-body">
                        <canvas id="enrollmentChart" style="min-height:250px; height:250px; max-height:250px; width:100%;"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bell mr-1"></i>
                            ស្ថានភាពត្រូវតាមដាន
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>បញ្ហាបន្ទាន់</span>
                            <strong class="text-danger">{{ number_format($urgentLearningIssues ?? 0) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span>វិញ្ញាបនបត្ររង់ចាំ</span>
                            <strong class="text-warning">{{ number_format($pendingCertificateRequests ?? 0) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @can('activity_logs.view')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-1"></i>
                            កំណត់ត្រាសកម្មភាពថ្មីៗ
                        </h3>
                        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-outline-primary">
                            មើលទាំងអស់
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>ពេលវេលា</th>
                                        <th>អ្នកប្រើ</th>
                                        <th>សកម្មភាព</th>
                                        <th>ពិពណ៌នា</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentActivityLogs ?? [] as $activity)
                                        @php($meta = \App\Models\ActivityLog::actionMeta($activity->action))
                                        <tr>
                                            <td>{{ $activity->created_at?->diffForHumans() }}</td>
                                            <td>{{ $activity->user->username ?? 'System' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $meta['badge'] }}">
                                                    <i class="{{ $meta['icon'] }} mr-1"></i>
                                                    {{ str($activity->action)->replace('_', ' ')->title() }}
                                                </span>
                                            </td>
                                            <td>{{ $activity->description }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">មិនទាន់មានកំណត់ត្រាសកម្មភាព</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bolt mr-1"></i>
                            សកម្មភាពរហ័ស
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="row text-center">
                            @can('students.manage')
                                <div class="col-md-3 col-6 mb-3">
                                    <a href="{{ route('admin.students.create') }}" class="btn btn-app bg-success w-100">
                                        <i class="fas fa-user-plus"></i>
                                        បន្ថែមសិស្ស
                                    </a>
                                </div>
                            @endcan

                            @can('courses.manage')
                                <div class="col-md-3 col-6 mb-3">
                                    <a href="{{ route('admin.courses.create') }}" class="btn btn-app bg-primary w-100">
                                        <i class="fas fa-plus"></i>
                                        បង្កើតវគ្គសិក្សា
                                    </a>
                                </div>
                            @endcan

                            @can('announcements.manage')
                                <div class="col-md-3 col-6 mb-3">
                                    <a href="{{ route('admin.announcements.create') }}" class="btn btn-app bg-info w-100">
                                        <i class="fas fa-bullhorn"></i>
                                        បង្កើតសេចក្តីជូនដំណឹង
                                    </a>
                                </div>
                            @endcan

                            @can('learning_issues.view')
                                <div class="col-md-3 col-6 mb-3">
                                    <a href="{{ route('admin.learning-issues.index') }}" class="btn btn-app bg-warning w-100">
                                        <i class="fas fa-life-ring"></i>
                                        ពិនិត្យបញ្ហាសិក្សា
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="{{ asset('backend/plugins/chart.js/Chart.min.js') }}"></script>
<script>
    const enrollmentCanvas = document.getElementById('enrollmentChart');

    if (enrollmentCanvas) {
        new Chart(enrollmentCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($studentRegistrationLabels ?? []),
                datasets: [{
                    label: 'សិស្ស',
                    data: @json($studentRegistrationTrend ?? []),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0,123,255,0.1)',
                    fill: true,
                    lineTension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#007bff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }]
                }
            }
        });
    }
</script>
@endpush
