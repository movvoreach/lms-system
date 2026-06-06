@extends('admin.layouts.master')

@section('title', 'Learning Issue Analytics')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <h1 class="mb-1">Learning Issue Analytics</h1>
            <p class="text-muted mb-0">Track student difficulty trends and support response workload.</p>
        </div>
    </section>

    <div class="row">
        @foreach ([['Total', $total, 'info'], ['Open', $open, 'warning'], ['Resolved', $resolved, 'success'], ['Urgent', $urgent, 'danger']] as $card)
            <div class="col-lg-3 col-6">
                <div class="small-box bg-{{ $card[2] }}">
                    <div class="inner">
                        <h3>{{ $card[1] }}</h3>
                        <p>{{ $card[0] }}</p>
                    </div>
                    <div class="icon"><i class="fas fa-life-ring"></i></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header"><h3 class="card-title">By Issue Type</h3></div>
                <div class="card-body">
                    @foreach ($issueTypes as $key => $label)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>{{ $label }}</span>
                            <strong>{{ $byType[$key] ?? 0 }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header"><h3 class="card-title">By Status</h3></div>
                <div class="card-body">
                    @foreach ($statuses as $key => $label)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>{{ $label }}</span>
                            <strong>{{ $byStatus[$key] ?? 0 }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
