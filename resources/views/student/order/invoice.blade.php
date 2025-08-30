@extends('backend.layouts.master')
@section('title', 'Student | My Course Details')
@section('main-content')

<div class="container my-5">
    <div class="card p-4 shadow border-0 rounded-4">
        <h2 class="text-center mb-4 text-primary">Invoice</h2>

        <div class="row mb-3">
            <div class="col-md-6">
                <h5>Order Info:</h5>
                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('d M, Y') }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge 
                        {{ $order->status == 'approved' ? 'bg-success' : ($order->status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <h5>Student Info:</h5>
                <p><strong>Name:</strong> {{ $order->user?->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $order->user?->email ?? 'N/A' }}</p>
            </div>
        </div>

        <h5 class="mt-4">Courses:</h5>
        <table class="table table-bordered table-hover mt-2">
            <thead class="table-light">
                <tr>
                    <th>SL</th>
                    <th>Course Title</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->course?->title ?? 'N/A' }}</td>
                        <td>{{ $item->price }} TK</td>
                    </tr>
                    @php $total += $item->price; @endphp
                @endforeach
                <tr class="table-secondary fw-bold">
                    <td colspan="2" class="text-end">Total</td>
                    <td>{{ $total }} TK</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-4">
            <h5>Payment Info:</h5>
            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
            <p><strong>Bkash Number:</strong> {{ $order->number ?? 'N/A' }}</p>
            <p><strong>Transaction ID:</strong> {{ $order->transaction_id ?? 'N/A' }}</p>
        </div>

        <div class="text-center mt-4">
            <button onclick="window.print()" class="btn btn-primary me-2"><i class="fas fa-print me-1"></i> Print Invoice</button>
            <a href="{{ route('student.course.order') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>
</div>
@endsection