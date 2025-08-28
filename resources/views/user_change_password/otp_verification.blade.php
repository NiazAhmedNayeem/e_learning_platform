@extends('backend.layouts.master')
@section('title', 'OTP Verification')
@section('main-content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card shadow-lg border-0 rounded-3">

                {{-- <form action="{{ route('password.change.verify') }}" method="POST">
                    @csrf
                
                    <div style="background-color: #212529" class="card-footer text-center">
                        <h3 class="text-white">
                        Welcome to {{ $user->name }} profile!
                        </h3>
                    </div>
                    <div class="row g-0">

                        <!-- Left Side (Image + Basic Info) -->
                        <div class="col-md-4 bg-light text-center p-4 d-flex flex-column align-items-center justify-content-center">
                            <!-- Profile Image -->
                            @if($user->image)
                                <img src="{{ $user->image_show }}" 
                                    alt="Profile Image" 
                                    class="rounded-circle img-thumbnail mb-3 shadow-sm" 
                                    style="width: 160px; height: 160px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/default-avatar.png') }}" 
                                    alt="Default Image" 
                                    class="rounded-circle img-thumbnail mb-3 shadow-sm" 
                                    style="width: 160px; height: 160px; object-fit: cover;">
                            @endif

                            <!-- Name -->
                            <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->profession ?? 'No Profession Added' }}</p>
                        
                            <p class="bg-success text-white text-uppercase px-3 py-1 rounded-pill mb-3">
                                {{ ucfirst($user->role) }}
                            </p>

                        </div>

                        <!-- Right Side (Details) -->
                        <div class="col-md-8 p-4">
                            <h5 class="mb-4 border-bottom pb-2 text-primary fw-bold">OTP Verification for Change Password</h5>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="otp" class="form-label fw-bold">Enter OTP</label>
                                    <div class="input-group">
                                        <input type="text" name="otp" id="otp"
                                            class="form-control @error('otp') is-invalid @enderror"
                                            required>
                                    </div>
                                    @if(session('success'))
                                        <div style="color: green">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    @error('otp') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <form method="POST" action="{{ route('password.change.resend') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0">Resend OTP</button>
                                </form>
                            </div>
                            
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="card-footer text-center bg-white">

                        <div class="d-flex justify-content-end gap-2 mt-1">
                            <a href="{{ route('teacher.profile') }}" class="btn btn-warning btn-lg shadow-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">Verify & Update</button>
                        </div>
                    </div>
                </form> --}}


                <form action="{{ route('password.change.verify') }}" method="POST">
    @csrf
    <div style="background-color: #212529" class="card-footer text-center">
        <h3 class="text-white">
            Welcome to {{ $user->name }} profile!
        </h3>
    </div>
    <div class="row g-0">
        <!-- Left Side -->
        <div class="col-md-4 bg-light text-center p-4 d-flex flex-column align-items-center justify-content-center">
           
                <img src="{{ $user->image_show }}" alt="Profile Image" class="rounded-circle img-thumbnail mb-3 shadow-sm" style="width: 160px; height: 160px; object-fit: cover;">
            

            <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
            <p class="text-muted">{{ $user->profession ?? 'No Profession Added' }}</p>
            <p class="bg-success text-white text-uppercase px-3 py-1 rounded-pill mb-3">
                {{ ucfirst($user->role) }}
            </p>
        </div>

        <!-- Right Side -->
        <div class="col-md-8 p-4">
            <h5 class="mb-4 border-bottom pb-2 text-primary fw-bold">OTP Verification for Change Password</h5>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="otp" class="form-label fw-bold">Enter OTP</label>
                    <div class="input-group">
                        <input type="text" name="otp" id="otp"
                            class="form-control @error('otp') is-invalid @enderror"
                            required>
                    </div>
                    @if(session('success'))
                        <div style="color: green">
                            {{ session('success') }}
                        </div>
                    @endif
                    @error('otp') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="card-footer text-center bg-white">
        <div class="d-flex justify-content-between align-items-center mt-1">
            <!-- Resend OTP Form -->
            <form method="POST" action="{{ route('password.change.resend') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0">Resend OTP</button>
            </form>

            <div class="d-flex gap-2">
                <a href="{{ route('teacher.profile') }}" class="btn btn-warning btn-lg shadow-sm">Cancel</a>
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">Verify & Update</button>
            </div>
        </div>
    </div>
</form>
                            
            </div>

        </div>
    </div>
</div>

@endsection


