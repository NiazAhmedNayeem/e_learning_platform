@extends('backend.layouts.master')
@section('title', 'Student | My Course Details')
@section('main-content')

<div class="container my-4">
    <div class="card shadow-lg rounded-3 border-0">
        <!-- Header -->
        <div style="background-color: #212529" class="card-header text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Course Details</h4>
            <span class="badge bg-warning text-dark">{{ $course->category?->name ?? 'N/A' }}</span>
            <a href="{{ route('student.courses') }}" class="btn btn-danger btn-sm">⬅ Back</a>
        </div>

        <div class="card-body">
            <div class="row align-items-start">
                <!-- Image -->
                <div class="col-md-4 text-center mb-3">
                    <img src="{{ $course->image_show }}" 
                         alt="{{ $course->title }}" 
                         class="img-fluid rounded shadow" style="max-height: 280px; object-fit: cover;">

                    <!-- Price Section -->
                    <div class="mt-4 p-3 bg-light rounded shadow-sm">
                        <h5 class="fw-bold mb-2">Pricing</h5>

                        @if($course->discount && $course->final_price < $course->price)
                            <p class="mb-1">
                                <span class="text-muted">Regular Price:</span> 
                                <del>{{ number_format($course->price, 2) }} TK</del>
                            </p>
                            <p class="mb-1">
                                <span class="text-muted">Discount:</span> 
                                <span class="text-danger">- {{ number_format($course->discount) }} %</span>
                            </p>
                            <p class="fw-bold text-success fs-5">
                                After Discount: {{ number_format($course->final_price, 2) }} TK
                            </p>
                        @else
                            <p class="fw-bold text-success fs-5">
                                Price: {{ number_format($course->price, 2) }} TK
                            </p>
                        @endif
                    </div>


                    <!-- Status -->
                    <div class="mt-3">
                        <h6 class="fw-bold">Status:</h6>
                        @if($course->status == 1)
                            <span class="badge bg-success px-3 py-2">Active</span>
                        @else
                            <span class="badge bg-danger px-3 py-2">Inactive</span>
                        @endif
                    </div>
                </div>

                <!-- Details -->
                <div class="col-md-8">
                    <h4 class="fw-bold">📘 {{ $course->title }}</h4>
                    <hr>

                    <h5 class="fw-bold">📌 Short Description</h5>
                    <p class="text-muted">{!! $course->short_description !!}</p>
                    <hr>

                    <h5 class="fw-bold">📖 Long Description</h5>
                    <p>{!! $course->long_description !!}</p>
                    <hr>
                    @if(trim(strip_tags($course->prerequisite)) !== '')
                        <h5 class="fw-bold">📝 Prerequisite</h5>
                        <p>{!! $course->prerequisite !!}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer text-muted text-end">
            Last Updated: {{ $course->updated_at->setTimezone('Asia/Dhaka')->format('d M, Y h:i A') }}
        </div>
    </div>
</div>

@endsection
