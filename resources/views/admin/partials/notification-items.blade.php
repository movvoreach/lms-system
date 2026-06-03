@forelse ($notifications as $notification)
    @php($data = $notification->data)
    <a href="{{ $data['url'] ?? '#' }}" class="dropdown-item js-notification-link" data-id="{{ $notification->id }}">
        <i class="{{ $data['icon'] ?? 'far fa-bell text-info' }} mr-2"></i>
        {{ $data['title'] ?? 'ការជូនដំណឹង' }}
        <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
        @if (! empty($data['message']))
            <small class="d-block text-muted text-truncate">{{ $data['message'] }}</small>
        @endif
    </a>
    <div class="dropdown-divider"></div>
@empty
    <a href="#" class="dropdown-item text-muted text-center">មិនមានការជូនដំណឹងថ្មី</a>
@endforelse
