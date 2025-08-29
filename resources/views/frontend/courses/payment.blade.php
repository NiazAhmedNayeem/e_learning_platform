@extends('backend.layouts.master')
@section('title', 'Payment')
@section('main-content')

<div class="container my-5">
    <h2 class="mb-4 text-center fw-bold text-primary">Proceed to Payment</h2>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4 p-4">

                {{--  Order Summary --}}
                <div class="mb-4 text-center">
                    <h4 class="fw-bold text-dark">Order Summary</h4>
                    <hr class="w-25 mx-auto">
                </div>

                @if(isset($course))
                    {{--  Single course checkout --}}
                    <div class="text-center mb-4">
                        <img src="{{ $course->image_show }}" width="90" height="90" class="rounded mb-2 shadow-sm">
                        <h5 class="fw-bold">{{ $course->title }}</h5>
                        <p class="text-muted mb-1">Teacher: {{ $course->teacher?->name ?? 'N/A' }}</p>
                        <p class="mb-1">Price: <span class="fw-bold">{{ $course->price }} TK</span></p>
                        @if($course->discount)
                            <p class="mb-1 text-success">Discount: {{ $course->discount }}%</p>
                        @endif
                        <h4 class="text-success fw-bold mt-2">Payable: {{ $amount }} TK</h4>
                    </div>
                @elseif(isset($cartItems))
                    {{--  Cart checkout --}}
                    <ul class="list-group mb-3 shadow-sm">
                        @foreach($cartItems as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $item->course->title ?? 'N/A' }}</span>
                                <span class="fw-bold text-dark">{{ $item->course->final_price ?? '0' }} TK</span>
                            </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between fw-bold bg-light">
                            Total
                            <span class="text-success">{{ $amount }} TK</span>
                        </li>
                    </ul>
                @endif

                {{--  Payment Info --}}
                <div class="card border-0 rounded-3 shadow-sm p-3 mb-4 text-center bg-light">
                    <h5 class="card-title mb-2">
                        <i class="fas fa-mobile-alt text-danger me-2"></i> Pay via bKash / Nagad
                    </h5>
                    <p class="fw-bold fs-5 mb-1 text-dark">01966 509 310</p>
                    <small class="text-muted">Use this number to complete your payment</small>

                    <div class="mt-3">
                        <span class="badge bg-danger me-2 px-3 py-2">
                            <i class="fas fa-money-bill-wave me-1"></i> bKash
                        </span>
                        <span class="badge bg-warning text-dark px-3 py-2">
                            <i class="fas fa-money-bill-wave me-1"></i> Nagad
                        </span>
                    </div>
                </div>

                {{--  Payment Options --}}
                <div class="mt-3">
                    <button class="btn btn-danger w-100 mb-2 fw-bold" data-bs-toggle="modal" data-bs-target="#bkashModal">
                        <i class="fas fa-mobile-alt me-2"></i> Pay with bKash
                    </button>

                    <button class="btn btn-warning w-100 mb-2 fw-bold" data-bs-toggle="modal" data-bs-target="#nagadModal">
                        <i class="fas fa-mobile-alt me-2"></i> Pay with Nagad
                    </button>

                    {{-- <button class="btn btn-primary w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#cardModal">
                        <i class="fas fa-credit-card me-2"></i> Pay with Card
                    </button> --}}
                </div>
            </div>
        </div>
    </div>

    {{--  Back to Courses --}}
    <div class="text-center mt-4">
        <a href="{{ route('frontend.courses') }}" class="btn btn-outline-secondary px-4">
            <i class="fas fa-arrow-left me-1"></i> Back to Courses
        </a>
    </div>
</div>

{{-- bKash Modal --}}
<div class="modal fade" id="bkashModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="#">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-mobile-alt me-2"></i> Pay with bKash</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">bKash Number</label>
                    <input type="text" name="mobile" class="form-control" required placeholder="01XXXXXXXXX">
                    <label class="form-label mt-3">Transaction ID</label>
                    <input type="text" name="transaction_id" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger w-100 fw-bold">Confirm Payment</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Nagad Modal --}}
<div class="modal fade" id="nagadModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="#">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="fas fa-wallet me-2"></i> Pay with Nagad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Nagad Number</label>
                    <input type="text" name="mobile" class="form-control" required placeholder="01XXXXXXXXX">
                    <label class="form-label mt-3">Transaction ID</label>
                    <input type="text" name="transaction_id" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning w-100 fw-bold">Confirm Payment</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Card Modal --}}
{{-- <div class="modal fade" id="cardModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="#">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-credit-card me-2"></i> Pay with Card</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Card Number</label>
                    <input type="text" name="card" class="form-control" required placeholder="XXXX-XXXX-XXXX-XXXX">
                    <label class="form-label mt-3">Expiry Date</label>
                    <input type="text" name="expiry" class="form-control" required placeholder="MM/YY">
                    <label class="form-label mt-3">CVC</label>
                    <input type="text" name="cvc" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Confirm Payment</button>
                </div>
            </div>
        </form>
    </div>
</div> --}}

@endsection
