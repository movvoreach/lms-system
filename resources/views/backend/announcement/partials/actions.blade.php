<div class="btn-group btn-group-sm" role="group">
    <a href="{{ route('admin.announcements.show', $announcement) }}" class="btn btn-primary">View</a>
    @can('announcements.manage')
        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-info">Edit</a>
        @if ($announcement->status !== 'published')
            <form method="POST" action="{{ route('admin.announcements.publish', $announcement) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm">Publish</button>
            </form>
        @endif
        @if ($announcement->status !== 'archived')
            <form method="POST" action="{{ route('admin.announcements.archive', $announcement) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm">Archive</button>
            </form>
        @endif
        <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form>
    @endcan
</div>
