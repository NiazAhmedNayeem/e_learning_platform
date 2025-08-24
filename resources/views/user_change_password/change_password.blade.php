@extends('backend.layouts.master')
@section('title', 'Reset Profile Password')
@section('main-content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card shadow-lg border-0 rounded-3">

                <form action="{{ route('password.change.request') }}" method="POST">
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
                            <h5 class="mb-4 border-bottom pb-2 text-primary fw-bold">Reset Profile Password</h5>
                            
                        <div class="row">

                                <div class="col-md-12 mb-3">
                                    <label for="current_password" class="form-label fw-bold">Current Password</label>
                                    <div class="input-group">
                                        <input type="password" name="current_password" id="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('current_password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="new_password" class="form-label fw-bold">New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="new_password" id="new_password"
                                            class="form-control @error('new_password') is-invalid @enderror" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('new_password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="new_password_confirmation" class="form-label fw-bold">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                            class="form-control @error('new_password_confirmation') is-invalid @enderror" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('new_password_confirmation') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                        </div>
                            
                            
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="card-footer text-center bg-white">

                        <div class="d-flex justify-content-end gap-2 mt-1">
                            @if ($user->role == 'teacher')
                                <a href="{{ route('teacher.profile') }}" class="btn btn-warning btn-lg shadow-sm">Cancel</a>
                            @elseif ($user->role == 'student')
                                <a href="{{ route('student.profile') }}" class="btn btn-warning btn-lg shadow-sm">Cancel</a>
                            @endif
                            
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">Reset Password</button>
                        </div>
                    </div>

                
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }
</script>


@endsection
