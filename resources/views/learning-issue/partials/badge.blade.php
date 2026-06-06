@php
    $classes = [
        'status' => [
            'open' => 'badge-danger',
            'in_progress' => 'badge-info',
            'waiting_student' => 'badge-warning',
            'resolved' => 'badge-success',
            'closed' => 'badge-secondary',
        ],
        'priority' => [
            'low' => 'badge-secondary',
            'normal' => 'badge-info',
            'high' => 'badge-warning',
            'urgent' => 'badge-danger',
        ],
    ];
@endphp

<span class="badge {{ $classes[$type][$value] ?? 'badge-secondary' }}">
    {{ \Illuminate\Support\Str::of($value)->replace('_', ' ')->title() }}
</span>
