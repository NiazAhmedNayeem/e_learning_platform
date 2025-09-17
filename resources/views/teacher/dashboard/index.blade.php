@extends('backend.layouts.master')
@section('title', 'Teacher Dashboard')
@section('main-content')
    
@if (auth()->check() && auth()->user()->status == 1)
    <h1 class="mt-4">Teacher Dashboard</h1>
    {{-- <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol> --}}
    
    
    {{-- Dashboard Cards --}}
    <div class="row">
        <style>
            .card-hover {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border-radius: 15px;
            }
            .card-hover:hover {
                transform: translateY(-8px) scale(1.03);
                box-shadow: 0 12px 30px rgba(0,0,0,0.3);
            }
        </style>

        {{-- Total Amount --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <a class="text-decoration-none">
                <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #1d2671, #c33764);">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                        <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                        <h6 class="text-uppercase fw-bold mb-1">Total Amount Received</h6>
                        <h2 class="fw-bold totalAmount">00.00 ৳</h2>
                    </div>
                </div>
            </a>
        </div>

        {{-- Total Assigned Course --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <a class="text-decoration-none" href="{{ route('teacher.assign.courses') }}">
                <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #00b09b, #96c93d);">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                        <i class="fa-solid fa-book-open-reader fa-2x mb-2"></i>
                        <h6 class="text-uppercase fw-bold mb-1">Total Assigned Course</h6>
                        <h2 class="fw-bold completeOrder">{{ $assign_courses }}</h2>
                    </div>
                </div>
            </a>
        </div>

        {{-- Total Students --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <a class="text-decoration-none" href="{{ route('teacher.course.student') }}">
                <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #ff512f, #dd2476);">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                        <i class="fas fa-user-graduate fa-2x mb-2"></i>
                        <h6 class="text-uppercase fw-bold mb-1">Total Students</h6>
                        <h2 class="fw-bold pendingOrder">{{ $students }}</h2>
                    </div>
                </div>
            </a>
        </div>

        
        <div class="col-xl-3 col-md-6 mb-4">
            <a class="text-decoration-none" href="{{ route('admin.order.index') }}">
                <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #f12711, #f5af19);">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                        <i class="fas fa-times-circle fa-2x mb-2"></i>
                        <h6 class="text-uppercase fw-bold mb-1">Total Reject Orders</h6>
                        <h2 class="fw-bold rejectOrder">00</h2>
                    </div>
                </div>
            </a>
        </div>

        
        {{-- <div class="col-xl-3 col-md-6 mb-4">
            <a class="text-decoration-none" href="{{ route('admin.student.index') }}">
                <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #36d1dc, #5b86e5);">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                        <i class="fas fa-user-graduate fa-2x mb-2"></i>
                        <h6 class="text-uppercase fw-bold mb-1">Total Students</h6>
                        <h2 class="fw-bold students">00</h2>
                    </div>
                </div>
            </a>
        </div>

        
        <div class="col-xl-3 col-md-6 mb-4">
            <a class="text-decoration-none" href="{{ route('admin.all-teacher') }}">
                <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #ff6a00, #ee0979);">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                        <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                        <h6 class="text-uppercase fw-bold mb-1">Total Teachers</h6>
                        <h2 class="fw-bold teachers">00</h2>
                    </div>
                </div>
            </a>
        </div> --}}

        

    </div>
    
    

    {{-- Notices Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Latest Notices</h5>
                    <a href="#" class="btn btn-light btn-sm">View All</a>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>System Maintenance</strong>
                                <p class="mb-0 text-muted small">Scheduled maintenance on 20th Sept 2025 from 10 PM to 12 AM.</p>
                            </div>
                            <span class="badge bg-warning text-dark">Admin</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>New Course Available</strong>
                                <p class="mb-0 text-muted small">“Advanced Laravel” course is now live for all students.</p>
                            </div>
                            <span class="badge bg-success">Teacher</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Exam Schedule</strong>
                                <p class="mb-0 text-muted small">Mid-term exams will start from 25th Sept 2025.</p>
                            </div>
                            <span class="badge bg-info text-dark">Admin</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Update Profile</strong>
                                <p class="mb-0 text-muted small">All teachers must update their profile by 22nd Sept.</p>
                            </div>
                            <span class="badge bg-primary">Teacher</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>New Feature: Chat</strong>
                                <p class="mb-0 text-muted small">Student-teacher chat feature is now available.</p>
                            </div>
                            <span class="badge bg-secondary">System</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>




@else
    @if (auth()->check() && auth()->user()->status == 0)
        <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="card shadow-lg border-0 text-center p-5" style="max-width: 600px;">
                <div class="card-body">
                    <h2 class="text-warning mb-3">
                        <i class="fas fa-user-slash"></i> Account Inactive
                    </h2>
                    <p class="fs-5 text-muted">
                        Your account is currently inactive.  
                        Please contact the system administrator to resolve this issue  
                        and regain access to your account.
                    </p>
                </div>
            </div>
        </div>
    @elseif (auth()->check() && auth()->user()->status == 2)
        <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="card shadow-lg border-0 text-center p-5" style="max-width: 600px;">
                <div class="card-body">
                    <h2 class="text-warning mb-3">
                        <i class="fas fa-hourglass-half"></i> Account Pending
                    </h2>
                    <p class="fs-5 text-muted">
                        Your account is currently under review. Please update your <a href="{{ route('teacher.profile') }}" class="text-decoration-none">profile</a> information and wait for approval.  
                        Thank you for your patience.
                    </p>
                </div>
            </div>
        </div>

    @elseif (auth()->check() && auth()->user()->status == 3)
            <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="card shadow-lg border-0 text-center p-5" style="max-width: 600px;">
                <div class="card-body">
                    <h2 class="text-danger mb-3">
                        <i class="fas fa-times-circle"></i> Account Rejected
                    </h2>
                    <p class="fs-5 text-muted">
                        Unfortunately, your account registration request has been rejected.  
                        If you believe this is a mistake or would like further assistance,  
                        please contact the system administrator.
                    </p>
                </div>
            </div>
        </div>
    @endif
@endif
           
@endsection

@section('scripts')

@endsection