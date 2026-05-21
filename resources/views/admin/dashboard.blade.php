@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')

<section class="content mt-3">

    {{-- Header --}}
    <div class="content-header">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h1 class="m-0">
                    LMS Dashboard
                </h1>

                <ol class="breadcrumb float-sm-right mb-0">
                    <li class="breadcrumb-item">
                        <a href="#">Home</a>
                    </li>

                    <li class="breadcrumb-item active">
                        Dashboard
                    </li>
                </ol>

            </div>

        </div>
    </div>

    {{-- Main Content --}}
    <div class="container-fluid">

        {{-- Small Boxes --}}
        <div class="row">

            <div class="col-lg-3 col-6">

                <div class="small-box bg-info">

                    <div class="inner">
                        <h3>150</h3>
                        <p>Total Courses</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-book"></i>
                    </div>

                    <a href="#" class="small-box-footer">
                        More info
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>

                </div>

            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-success">

                    <div class="inner">
                        <h3>120</h3>
                        <p>Active Courses</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>

                    <a href="#" class="small-box-footer">
                        More info
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>

                </div>

            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-warning">

                    <div class="inner">
                        <h3>850</h3>
                        <p>Total Students</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    <a href="#" class="small-box-footer">
                        More info
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>

                </div>

            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-danger">

                    <div class="inner">
                        <h3>45</h3>
                        <p>Total Teachers</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>

                    <a href="#" class="small-box-footer">
                        More info
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>

                </div>

            </div>

        </div>

        {{-- Info Boxes --}}
        <div class="row">

            <div class="col-lg-3 col-6">

                <div class="info-box">

                    <span class="info-box-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">Students</span>
                        <span class="info-box-number">850</span>
                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-6">

                <div class="info-box">

                    <span class="info-box-icon bg-info">
                        <i class="fas fa-user-plus"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">New Today</span>
                        <span class="info-box-number">25</span>
                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-6">

                <div class="info-box">

                    <span class="info-box-icon bg-warning">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">Teachers</span>
                        <span class="info-box-number">45</span>
                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-6">

                <div class="info-box">

                    <span class="info-box-icon bg-success">
                        <i class="fas fa-book-open"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">Lessons</span>
                        <span class="info-box-number">320</span>
                    </div>

                </div>

            </div>

        </div>

        {{-- Table + Chart --}}
        <div class="row">

            {{-- Recent Students --}}
            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header border-0 d-flex justify-content-between align-items-center">

                        <h3 class="card-title">
                            <i class="fas fa-list mr-1"></i>
                            Recent Students
                        </h3>

                        <a href="#" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i>
                            View All
                        </a>

                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover table-striped mb-0">

                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Gender</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>
                                        <td>ST001</td>
                                        <td>John Doe</td>
                                        <td>john@example.com</td>
                                        <td>Male</td>
                                    </tr>

                                    <tr>
                                        <td>ST002</td>
                                        <td>Jane Smith</td>
                                        <td>jane@example.com</td>
                                        <td>Female</td>
                                    </tr>

                                    <tr>
                                        <td>ST003</td>
                                        <td>Michael Brown</td>
                                        <td>michael@example.com</td>
                                        <td>Male</td>
                                    </tr>

                                    <tr>
                                        <td>ST004</td>
                                        <td>Emily Johnson</td>
                                        <td>emily@example.com</td>
                                        <td>Female</td>
                                    </tr>

                                    <tr>
                                        <td>ST005</td>
                                        <td>David Wilson</td>
                                        <td>david@example.com</td>
                                        <td>Male</td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Chart --}}
            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header border-0">

                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-1"></i>
                            Student Registration Trend
                        </h3>

                    </div>

                    <div class="card-body">

                        <canvas id="enrollmentChart"
                                style="min-height:250px; height:250px; max-height:250px; width:100%;">
                        </canvas>

                    </div>

                </div>

            </div>

        </div>

        {{-- Quick Actions --}}
        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">

                        <h3 class="card-title">
                            <i class="fas fa-bolt mr-1"></i>
                            Quick Actions
                        </h3>

                    </div>

                    <div class="card-body">

                        <div class="row text-center">

                            <div class="col-md-3 col-6 mb-3">
                                <a href="#" class="btn btn-app bg-success w-100">
                                    <i class="fas fa-user-plus"></i>
                                    Add Student
                                </a>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <a href="#" class="btn btn-app bg-primary w-100">
                                    <i class="fas fa-plus"></i>
                                    New Course
                                </a>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <a href="#" class="btn btn-app bg-info w-100">
                                    <i class="fas fa-user-check"></i>
                                    Enrollments
                                </a>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <a href="#" class="btn btn-app bg-warning w-100">
                                    <i class="fas fa-chart-bar"></i>
                                    Reports
                                </a>
                            </div>

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

    const ctx = document.getElementById('enrollmentChart').getContext('2d');

    new Chart(ctx, {

        type: 'line',

        data: {

            labels: [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June'
            ],

            datasets: [{
                label: 'Students',

                data: [
                    50,
                    80,
                    120,
                    160,
                    200,
                    250
                ],

                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.1)',
                fill: true,
                tension: 0.4
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false
        }

    });

</script>

@endpush
