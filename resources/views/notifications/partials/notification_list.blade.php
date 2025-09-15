@forelse ($notifications as $notification)
    @if(!$notification->read_at)
        <a href="{{ route('profile.notifications.read', $notification->id) }}" 
           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center list-group-item-warning mark-as-read">

            <div>
                {{ $notification->data['message'] ?? 'No message' }}
                <br>
                <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>
                    {{ $notification->created_at->setTimezone('Asia/Dhaka')->format('d M Y h:i A') }}
                </small>
            </div>
        </a>
    @else
        <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-secondary">
            <div>
                {{ $notification->data['message'] ?? 'No message' }}
                <br>
                <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>
                    {{ $notification->created_at->setTimezone('Asia/Dhaka')->format('d M Y h:i A') }}
                </small>
            </div>
        </li>
    @endif
@empty
    <li class="list-group-item">No notifications available.</li>
@endforelse

{{-- Only one pagination container --}}
@if($notifications->hasPages())
    <div id="pagination-links" class="mt-3">
        {{ $notifications->links('pagination::bootstrap-5') }}
    </div>
@endif
