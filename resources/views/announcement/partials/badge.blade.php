@php
    $classes = [
        'priority' => [
            'low' => 'badge-secondary',
            'normal' => 'badge-info',
            'high' => 'badge-warning',
            'urgent' => 'badge-danger',
        ],
        'status' => [
            'draft' => 'badge-secondary',
            'published' => 'badge-success',
            'archived' => 'badge-dark',
        ],
    ];
@endphp

<span class="badge {{ $classes[$type][$value] ?? 'badge-secondary' }}">
    {{ \Illuminate\Support\Str::of($value)->replace('_', ' ')->title() }}
</span>
