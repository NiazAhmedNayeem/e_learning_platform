@extends('backend.layouts.master')
@section('title', 'Checkout')
@section('main-content')

<div class="container my-5">
    <h2 class="mb-4">Checkout</h2>

    
        <div class="row">
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Course</th>
                                <th>Teacher</th>
                                <th>Price</th>
                                @if ($course->discount)
                                    <th>Discount</th>
                                    <th>Discount Price</th>
                                @endif
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="d-flex align-items-center gap-2">
                                    <img src="{{ $course->image_show }}" alt="Course" width="60" height="60" class="rounded">
                                    {{ $course->title }}
                                </td>
                                <td>{{ $course->teacher?->name ?? 'N/A' }}</td>
                                <td>{{ $course->price }} TK</td>
                                @if ($course->discount)
                                    <td class="text-success">{{ $course->discount }}%</td>
                                    <td>{{ $course->final_price }} TK</td>
                                @endif
                            </tr>
                            @if (!$course->discount)
                            <tr class="table-secondary">
                                <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                <td colspan="2"><strong>{{ $course->final_price }} TK</strong></td>
                            </tr>
                            @else
                            <tr class="table-secondary">
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td colspan="2"><strong>{{ $course->final_price }} TK</strong></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h5>Order Summary</h5>
                    <hr>
                    <p>Total: <strong>{{ $course->final_price }} TK</strong></p>
                    <a href="{{ route('frontend.payment.now', $course->id) }}" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-credit-card me-2"></i> Proceed to Payment
                    </a>
                </div>
            </div>
        </div>

  
        <div class="text-center py-5">
            
            <a href="{{ route('frontend.courses') }}" class="btn btn-primary mt-3">Browse Courses</a>
        </div>
    
</div>

@endsection
