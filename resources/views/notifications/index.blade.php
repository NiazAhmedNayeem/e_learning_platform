@extends('backend.layouts.master')
@section('title', 'Notifications')

@section('main-content')
<div class="container mt-4">
    <h4>Your Notifications</h4>
    <ul class="list-group mt-3">
        @forelse ($notifications as $notification)
            @if(!$notification->read_at)
                <a href="{{ route('profile.notifications.read', $notification->id) }}" 
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center list-group-item-warning">

                    <div>
                        {{ $notification->data['message'] ?? 'No message' }}
                        <br>
                        <small class="text-muted">
                            {{-- {{ $notification->created_at->format('d M Y h:i A') }} --}}
                            {{ $notification->created_at->setTimezone('Asia/Dhaka')->format('d M Y h:i A') }}
                            {{-- {{ $notification->created_at->timezone(auth()->user()->timezone ?? config('app.timezone'))->format('d M Y h:i A') }} --}}
                        </small>
                    </div>
                </a>
            @else
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        {{ $notification->data['message'] ?? 'No message' }}
                        <br>
                        <small class="text-muted">
                            {{-- {{ $notification->created_at->format('d M Y h:i A') }} --}}
                            {{-- {{ $notification->created_at->timezone(auth()->user()->timezone ?? config('app.timezone'))->format('d M Y h:i A') }} --}}
                            {{ $notification->created_at->setTimezone('Asia/Dhaka')->format('d M Y h:i A') }}
                        </small>
                    </div>
                </li>
            @endif
        @empty
            <li class="list-group-item">No notifications available.</li>
        @endforelse
    </ul>

    {{-- Pagination Links --}}
    <div class="mt-3">
        {{ $notifications->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection