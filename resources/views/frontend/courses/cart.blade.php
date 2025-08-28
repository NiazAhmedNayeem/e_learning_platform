@extends('backend.layouts.master')
@section('title', 'Your Cart')
@section('main-content')

<div class="container my-5">
    <h2 class="mb-4">Your Shopping Cart</h2>

    @if($cartItems->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>Price</th>
                        <th>Discount Price</th>
                        <th>Quantity</th>
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
                                <img src="{{ $item->course?->image_show }}" alt="Course" width="60" height="60" class="rounded">
                                {{ $item->course->title }}
                            </td>
                            <td>{{ $item->course->teacher?->name ?? 'N/A' }}</td>
                            <td>{{ $item->course?->price }} TK</td>
                            <td class="text-success">{{ $item->course?->final_price }} TK</td>
                            <td>1</td>
                            <td>{{ $totalPrice }} TK</td>
                            <td>
                                <form action="{{ route('frontend.cart.removed', $item->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="table-secondary">
                        <td colspan="5" class="text-end"><strong>Grand Total:</strong></td>
                        <td colspan="2"><strong>{{ $grandTotal }} TK</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('frontend.checkout') }}" class="btn btn-success btn-lg">Proceed to Checkout</a>
        </div>

    @else
        <div class="text-center py-5">
            <h4>Your cart is empty!</h4>
            <a href="{{ route('frontend.courses') }}" class="btn btn-primary mt-3">Browse Courses</a>
        </div>
    @endif
</div>

@endsection
