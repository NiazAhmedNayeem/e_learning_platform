@extends('backend.layouts.master')
@section('title', 'Admin Dashboard')
@section('main-content')

@if (auth()->check() && auth()->user()->status == 1)
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            {{-- <li class="breadcrumb-item active">Dashboard</li> --}}
        </ol>
        <div class="row">
            
            <div class="col-xl-3 col-md-6">
                <a class="text-decoration-none" href="{{ route('admin.student.index') }}">
                <div class="card text-white mb-4 shadow-lg" style="background: linear-gradient(135deg, #1d2671, #c33764);">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                        <h5 class="card-title text-uppercase fw-bold mb-2">Total Students</h5>
                        <h2 class="display-4 fw-bold mb-3">{{ $students }}</h2>
                    </div>
                </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6">
                <a class="text-decoration-none" href="{{ route('admin.all-teacher') }}">
                    <div class="card text-white mb-4 shadow-lg" style="background: linear-gradient(135deg, #1d2671, #c33764);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <h5 class="card-title text-uppercase fw-bold mb-2">Total Teachers</h5>
                            <h2 class="display-4 fw-bold mb-3">{{ $teachers }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            @if (auth()->check() && auth()->user()->role == 'admin' && auth()->user()->is_super == 1)
                <div class="col-xl-3 col-md-6">
                    <a class="text-decoration-none" href="{{ route('user.admin.index') }}">
                        <div class="card text-white mb-4 shadow-lg" style="background: linear-gradient(135deg, #1d2671, #c33764);">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                                <h5 class="card-title text-uppercase fw-bold mb-2">Total Admins</h5>
                                <h2 class="display-4 fw-bold mb-3">{{ $admins }}</h2>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
            
            <div class="col-xl-3 col-md-6">
                <a class="text-decoration-none" href="{{ route('admin.category.index') }}">
                    <div class="card text-white mb-4 shadow-lg" style="background: linear-gradient(135deg, #1d2671, #c33764);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <h5 class="card-title text-uppercase fw-bold mb-2">Total Categories</h5>
                            <h2 class="display-4 fw-bold mb-3">{{ $categories }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6">
                <a class="text-decoration-none" href="{{ route('admin.course.index') }}">
                    <div class="card text-white mb-4 shadow-lg" style="background: linear-gradient(135deg, #1d2671, #c33764);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <h5 class="card-title text-uppercase fw-bold mb-2">Total Courses</h5>
                            <h2 class="display-4 fw-bold mb-3">{{ $courses }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6">
                <a class="text-decoration-none" href="{{ route('admin.course_assign.index') }}">
                    <div class="card text-white mb-4 shadow-lg" style="background: linear-gradient(135deg, #1d2671, #c33764);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <h6 class="card-title text-uppercase fw-bold mb-2">Assigned Courses</h6>
                            <h2 class="display-4 fw-bold mb-3">{{ $assigned_courses }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            {{-- <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">All Student</div>
                    <h4>{{ $students }}</h4>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div> --}}
            
            {{-- <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">Warning Card</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">Success Card</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">Danger Card</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        Area Chart Example
                    </div>
                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i>
                        Bar Chart Example
                    </div>
                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                </div>
            </div>
        </div>
        
@else
        
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
                @php
                    $super_admin = App\Models\User::where('is_super', 1)->first();
                @endphp
                <p class="fs-5 text-muted mt-5">
                    Support Email: <span class="text-success">{{ $super_admin->email }}</span>
                </p>
            </div>
        </div>
    </div> 
@endif

                    
@endsection