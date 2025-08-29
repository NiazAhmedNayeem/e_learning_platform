@extends('backend.layouts.master')
@section('title', 'Checkout')
@section('main-content')

<div class="container my-5">
    <h2 class="mb-4">Checkout</h2>

    @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Course</th>
                                <th>Teacher</th>
                                <th>Price</th>
                                <th>Discount Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grandTotal = 0; @endphp
                            @foreach($cartItems as $item)
                                @php 
                                    $totalPrice = $item->course->final_price; 
                                    $grandTotal += $totalPrice;
                                @endphp
                                <tr>
                                    <td class="d-flex align-items-center gap-2">
                                        <img src="{{ $item->course->image_show }}" alt="Course" width="60" height="60" class="rounded">
                                        {{ $item->course->title }}
                                    </td>
                                    <td>{{ $item->course->teacher?->name ?? 'N/A' }}</td>
                                    <td>{{ $item->course->price }} TK</td>
                                    <td class="text-success">
                                        @if ($item->course->discount)
                                            {{ $item->course->final_price }} TK
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $totalPrice }} TK</td>
                                    <td>
                                        <form action="{{ route('frontend.cart.removed', $item->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="table-secondary">
                                <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                                <td colspan="2"><strong>{{ $grandTotal }} TK</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h5>Order Summary</h5>
                    <hr>
                    <p>Total Items: <strong>{{ $cartItems->count() }}</strong></p>
                    <p>Grand Total: <strong>{{ $grandTotal }} TK</strong></p>
                    <a href="{{ route('frontend.cart.payment') }}" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-credit-card me-2"></i> Proceed to Payment
                    </a>
                </div>
            </div>
        </div>

    @else
        <div class="text-center py-5">
            <h4>Your cart is empty!</h4>
            <a href="{{ route('frontend.courses') }}" class="btn btn-primary mt-3">Browse Courses</a>
        </div>
    @endif
</div>

@endsection
