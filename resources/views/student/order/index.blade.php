@extends('backend.layouts.master')
@section('title', 'Student | My Orders')
@section('main-content')

<div class="container mt-4">
    <h2 class="mb-4 text-center">My Orders</h2>

    {{-- Search --}}
    <div class="d-flex justify-content-end mb-3">
        <form class="d-flex" method="GET" action="{{ route('student.course.order') }}">
            <input class="form-control me-2" type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by course or order...">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    @forelse($orders as $order)
        <div class="card mb-3 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><strong>Order ID:</strong> #{{ $order->id }}</span>
                @php
                    $badgeClass = match($order->status) {
                        'approved' => 'bg-success',
                        'pending' => 'bg-secondary',
                        'rejected' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                    $statusText = ucfirst($order->status ?? '');
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
            </div>
            <div class="card-body">
                <h5 class="card-title">Courses in this order:</h5>
                <ul class="list-group">
                    @foreach($order->orderItems as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <img src="{{ $item->course?->image_show }}" alt="Course" width="40" height="40" class="rounded me-2">
                            {{ $item->course?->title ?? 'N/A' }}
                        </span>
                        <span class="fw-bold">{{ $item->price ?? 0 }} TK</span>
                    </li>
                    @endforeach
                </ul>
                <div class="mt-2 text-end">
                    <strong>Total: </strong>{{ $order->amount ?? 0 }} TK
                </div>

                {{-- Invoice Button --}}
                <div class="mt-3 text-end">
                    <a href="{{ route('student.order.invoice', $order->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-file-invoice me-1"></i> View Invoice
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            You have not placed any orders yet.
        </div>
    @endforelse


    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-3">
        {{ $orders->appends(['search' => $search])->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
