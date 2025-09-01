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
                    <a href="#" class="btn btn-warning btn-sm">View</a>
                    
                </td>
            </tr>
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