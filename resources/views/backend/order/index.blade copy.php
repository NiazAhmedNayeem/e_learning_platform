@extends('backend.layouts.master')
@section('title', 'Admin | Orders')
@section('main-content')

<div class="container mt-4">
    <h2>All Orders List</h2>

    <div class="d-flex justify-content-end mb-3">
        <form class="d-flex" method="GET" action="{{ route('admin.order.index') }}">
            <input class="form-control" type="text" name="search" value="{{ $search }}" placeholder="Search with course title..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Order ID</th>
                <th>Name</th>
                {{-- <th>Total orders</th> --}}
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Number</th>
                <th>Transaction ID</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            @forelse ($orders as $order)
            <tr>
                <td>{{ $loop->iteration + ($orders->currentPage()-1)*$orders->perPage() }}</td>
                <td>{{ $order->unique_order_id }}</td>
                <td>
                    <img src="{{ $order->user?->image_show }}" class="rounded-circle mb-2 shadow-sm" alt="student Image" width="40" height="40">
                    {{ explode(' ', $order->user?->name)[0] ?? '' }}
                </td>
                {{-- <td>{{ $order->orderItems?->count() }}</td> --}}
                <td>{{ $order->amount }} TK</td>
                <td>{{ $order->payment_method }}</td>
                <td>{{ $order->number }}</td>
                <td>{{ $order->transaction_id }}</td>
                <td>{{ $order->created_at }}</td>
                <td>
                    @switch($order->status)
                        @case('pending')
                            <span class="badge bg-warning">Pending</span>
                            @break

                        @case('approved')
                            <span class="badge bg-success">Approved</span>
                            @break

                        @case('rejected')
                            <span class="badge bg-danger">Rejected</span>
                            @break

                        @default
                            <span class="badge bg-secondary">Unknown</span>
                    @endswitch
                </td>

                <td>
                     <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#teacherModal{{ $order->id }}">
                        View
                    </button>
                    
                </td>
            </tr>





            <!-- Order Modal -->
            <div class="modal fade" id="teacherModal{{ $order->id }}" tabindex="-1" aria-labelledby="teacherModalLabel{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content shadow-lg border-0 rounded-3">
                        
                        {{-- Header --}}
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title fw-bold" id="teacherModalLabel{{ $order->id }}">
                                <i class="fas fa-shopping-cart me-2"></i> Order Details
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

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
                            <form action="{{ route('admin.order.status', $order->id) }}" method="POST">
                                <input name="status" type="hidden" value="approved">
                                @csrf
                                <button type="submit" class="btn btn-success"><i class="fas fa-check-circle me-1"></i> Approve</button>
                            </form>
                            @endif

                            @if($order->status != 'pending')
                            <form action="{{ route('admin.order.status', $order->id) }}" method="POST">
                                @csrf
                                <input name="status" type="hidden" value="pending">
                                <button type="submit" class="btn btn-warning"><i class="fas fa-clock me-1"></i> Pending</button>
                            </form>
                            @endif

                            @if($order->status != 'rejected')
                            <form action="{{ route('admin.order.status', $order->id) }}" method="POST">
                                @csrf
                                <input name="status" type="hidden" value="rejected">
                                <button type="submit" class="btn btn-danger"><i class="fas fa-times-circle me-1"></i> Reject</button>
                            </form>
                            @endif

                            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Close</button>
                        </div>
                    
                    
                    </div>
                </div>
            </div>                      

            <!-- End Modal -->


            @empty
            <tr>
                <td colspan="10" class="text-center">No Order Found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination Links --}}
    <div class="d-flex justify-content-end">
        {{ $orders->appends(['search' => $search])->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection