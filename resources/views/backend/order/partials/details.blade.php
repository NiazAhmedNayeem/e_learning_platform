{{-- Body --}}
<div class="modal-body">
    <div class="row g-4">
        {{-- Order Info --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary"><i class="fas fa-receipt me-2"></i>Order Info</h5>
                    <p><strong>Order ID:</strong> {{ $order->unique_order_id }}</p>
                    <p><strong>Date:</strong> {{ $order->created_at->format('d M, Y') }}</p>
                    <p>
                        <strong>Status:</strong> 
                        <span class="badge px-3 py-2
                            {{ $order->status == 'approved' ? 'bg-success' : 
                            ($order->status == 'pending' ? 'bg-warning text-dark' : 
                            ($order->status == 'rejected' ? 'bg-danger' : 'bg-secondary')) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Student Info --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary"><i class="fas fa-user me-2"></i>Student Info</h5>
                    <p><strong>Name:</strong> {{ $order->user?->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $order->user?->email ?? 'N/A' }}</p>
                    <p><strong>Payment:</strong> {{ $order->payment_method ?? 'N/A' }}</p>
                    <p><strong>{{ $order->payment_method ?? 'N/A' }} Number:</strong> {{ $order->number ?? 'N/A' }}</p>
                    <p><strong>Txn ID:</strong> {{ $order->transaction_id ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Courses --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title text-primary"><i class="fas fa-book me-2"></i>Courses</h5>
            <ul class="list-group">
                @foreach($order->orderItems as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <img src="{{ $item->course?->image_show }}" alt="Course" width="40" height="40" class="rounded me-2 shadow-sm">
                        {{ $item->course?->title ?? 'N/A' }}
                    </span>
                    <span class="fw-bold text-success">{{ $item->price ?? 0 }} TK</span>
                </li>
                @endforeach
            </ul>
            <div class="mt-3 text-end">
                <strong>Total:</strong> <span class="fs-5 text-dark">{{ $order->amount ?? 0 }} TK</span>
            </div>
            <div class="mt-3 text-end">
                <a href="{{ route('admin.order.invoice', $order->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-file-invoice me-1"></i> View Invoice
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Footer --}}
<div class="modal-footer d-flex flex-wrap gap-2 bg-light">
    @if($order->status != 'approved')
        <button type="button" class="btn btn-success updateStatus" data-id="{{ $order->id }}" data-status="approved">Approve</button>
    @endif

    @if($order->status != 'pending')
        <button type="button" class="btn btn-warning updateStatus" data-id="{{ $order->id }}" data-status="pending">Pending</button>
    @endif

    @if($order->status != 'rejected')
        <button type="button" class="btn btn-danger updateStatus" data-id="{{ $order->id }}" data-status="rejected">Reject</button>
    @endif

    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
</div>


