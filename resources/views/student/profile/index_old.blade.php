@extends('backend.layouts.master')
@section('title', 'Student Profile')
@section('main-content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">




            <div class="card shadow-lg border-0 rounded-3">



                <div style="background-color: #212529" class="card-footer text-center">
                    <h3 class="text-white">
                       Welcome to {{ $student->name }} profile!
                    </h3>
                </div>
                <div class="row g-0">

                    <!-- Left Side (Image + Basic Info) -->
                    <div class="col-md-4 bg-light text-center p-4 d-flex flex-column align-items-center justify-content-center">
                        <!-- Profile Image -->
                        @if($student->image)
                            <img src="{{ $student->image_show }}" 
                                 alt="Profile Image" 
                                 class="rounded-circle img-thumbnail mb-3 shadow-sm" 
                                 style="width: 160px; height: 160px; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&size=160" 
                                        class="rounded-circle img-thumbnail shadow-sm mb-2" 
                                        alt="Default Avatar">
                        @endif

                        <!-- Name -->
                        <h4 class="fw-bold mb-1">{{ $student->name }}</h4>
                        <p class="text-muted">{{ $student->profession ?? 'No Profession Added' }}</p>

                       @php
                            $roleClass = $student->role === 'student' ? 'bg-success' : 'bg-info';
                        @endphp

                        <p class="d-inline-block {{ $roleClass }} text-white text-uppercase px-3 py-1 rounded-pill mb-3">
                            {{ ucfirst($student->role) }}
                        </p>


                       
                    </div>

                    <!-- Right Side (Details) -->
                    <div class="col-md-8 p-4">
                        <h5 class="mb-4 border-bottom pb-2 text-primary fw-bold">Profile Details</h5>
                        
                        <!-- Bio -->
                        @if($student->bio)
                            <div class="bg-light p-3 mb-4 rounded shadow-sm">
                                <p class="text-secondary fst-italic mb-0">
                                    "{{ $student->bio }}"
                                </p>
                            </div>
                        @endif
                        
                        <!-- Details List -->
                        <ul class="list-group list-group-flush shadow-sm rounded">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>Student ID:</strong></span>
                               <span class="fw-bold">{{ $student->unique_id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>Email:</strong></span>
                                <span>{{ $student->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>Phone:</strong></span>
                                <span>{{ $student->phone ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>Gender:</strong></span>
                                <span>{{ ucfirst($student->gender) ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>Address:</strong></span>
                                <span>{{ $student->address ?? 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>

                </div>


                
                <!-- Footer -->
                <div class="card-footer text-center bg-white">

                    <div class="d-flex justify-content-end gap-2 mt-1">
                        <a href="{{ route('password.change.form') }}" class="btn btn-warning btn-lg shadow-sm">✏️ Change Password</a>
                        <a href="{{ route('student.profile.edit') }}" class="btn btn-primary btn-lg shadow-sm">✏️ Edit Profile</a>
                    </div>
                </div>
            </div>












        </div>
    </div>
</div>

@endsection
