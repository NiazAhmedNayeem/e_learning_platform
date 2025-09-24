@extends('backend.layouts.master')
@section('title', 'Admin Dashboard')
@section('main-content')

@if (auth()->check() && auth()->user()->status == 1)
        <h1 class="mt-2">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            {{-- <li class="breadcrumb-item active">Dashboard</li> --}}
        </ol>

        {{-- Filter Form --}}
        <div class="row mb-4">
            <div class="col-md-12 d-flex flex-wrap gap-2">
                {{-- Pre-defined buttons --}}
                <button class="btn btn-secondary filterBtn" data-filter="all">All</button>
                <button class="btn btn-primary filterBtn" data-filter="day">Today</button>
                <button class="btn btn-success filterBtn" data-filter="week">This Week</button>
                <button class="btn btn-warning filterBtn" data-filter="month">This Month</button>
                <button class="btn btn-danger filterBtn" data-filter="year">This Year</button>

                {{-- Custom Date Range --}}
                <input type="date" id="fromDate" class="form-control" style="max-width: 180px;">
                <input type="date" id="toDate" class="form-control" style="max-width: 180px;">
            </div>
        </div>


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
                <a class="text-decoration-none" href="{{ route('admin.order.index') }}">
                    <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #1d2671, #c33764);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                            <h6 class="text-uppercase fw-bold mb-1">Total Amount Received</h6>
                            <h2 class="fw-bold totalAmount">{{ number_format($totalAmount) }} ৳</h2>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Complete Orders --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <a class="text-decoration-none" href="{{ route('admin.order.index') }}">
                    <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #00b09b, #96c93d);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h6 class="text-uppercase fw-bold mb-1">Total Complete Orders</h6>
                            <h2 class="fw-bold completeOrder">{{ $completeOrder }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Pending Orders --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <a class="text-decoration-none" href="{{ route('admin.order.index') }}">
                    <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #ff512f, #dd2476);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <i class="fas fa-hourglass-half fa-2x mb-2"></i>
                            <h6 class="text-uppercase fw-bold mb-1">Total Pending Orders</h6>
                            <h2 class="fw-bold pendingOrder">{{ $pendingOrder }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Reject Orders --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <a class="text-decoration-none" href="{{ route('admin.order.index') }}">
                    <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #f12711, #f5af19);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <i class="fas fa-times-circle fa-2x mb-2"></i>
                            <h6 class="text-uppercase fw-bold mb-1">Total Reject Orders</h6>
                            <h2 class="fw-bold rejectOrder">{{ $rejectOrder }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Students --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <a class="text-decoration-none" href="{{ route('admin.student.index') }}">
                    <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #36d1dc, #5b86e5);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <i class="fas fa-user-graduate fa-2x mb-2"></i>
                            <h6 class="text-uppercase fw-bold mb-1">Total Students</h6>
                            <h2 class="fw-bold students">{{ $students }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Teachers --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <a class="text-decoration-none" href="{{ route('admin.all-teacher') }}">
                    <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #ff6a00, #ee0979);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                            <h6 class="text-uppercase fw-bold mb-1">Total Teachers</h6>
                            <h2 class="fw-bold teachers">{{ $teachers }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Admins --}}
            @if (auth()->check() && auth()->user()->role == 'admin' && auth()->user()->is_super == 1)
                <div class="col-xl-3 col-md-6 mb-4">
                    <a class="text-decoration-none" href="{{ route('user.admin.index') }}">
                        <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #0f2027, #2c5364);">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                                <i class="fas fa-user-shield fa-2x mb-2"></i>
                                <h6 class="text-uppercase fw-bold mb-1">Total Admins</h6>
                                <h2 class="fw-bold admins">{{ $admins }}</h2>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            {{-- Total Categories --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <a class="text-decoration-none" href="{{ route('admin.category.index') }}">
                    <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <i class="fas fa-th-large fa-2x mb-2"></i>
                            <h6 class="text-uppercase fw-bold mb-1">Total Categories</h6>
                            <h2 class="fw-bold categories">{{ $categories }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Courses --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <a class="text-decoration-none" href="{{ route('admin.course.index') }}">
                    <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #fc4a1a, #f7b733);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <i class="fas fa-book-open fa-2x mb-2"></i>
                            <h6 class="text-uppercase fw-bold mb-1">Total Courses</h6>
                            <h2 class="fw-bold courses">{{ $courses }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Assigned Courses --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <a class="text-decoration-none" href="{{ route('admin.course_assign.index') }}">
                    <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #8360c3, #2ebf91);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <i class="fas fa-tasks fa-2x mb-2"></i>
                            <h6 class="text-uppercase fw-bold mb-1">Assigned Courses</h6>
                            <h2 class="fw-bold assignedCourses">{{ $assigned_courses }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Active Notice --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <a class="text-decoration-none" href="{{ route('admin.notice.index') }}">
                    <div class="card text-white shadow-lg border-0 card-hover" style="background: linear-gradient(135deg, #36d1dc, #5b86e5);">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <i class="fa-solid fa-bullhorn fa-2x mb-2"></i>
                            <h6 class="text-uppercase fw-bold mb-1">Total Active Notice</h6>
                            <h2 class="fw-bold notices">{{ $notices }}</h2>
                        </div>
                    </div>
                </a>
            </div>

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
                <h2 class="text-warning mb-2">
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

@section('scripts')
<script src="{{ asset('public/backend/assets/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('public/backend/assets/demo/chart-bar-demo.js') }}"></script>
<script>
    $(document).ready(function(){

        function loadDashboard(filter = 'all', from = '', to = ''){
            $.ajax({
                url: "{{ route('admin.dashboard.filter') }}",
                type: "GET",
                data: {filter: filter, from: from, to: to},
                success: function(res){
                    $('.totalAmount').text(new Intl.NumberFormat().format(res.totalAmount) + ' ৳');
                    $('.completeOrder').text(res.completeOrder);
                    $('.pendingOrder').text(res.pendingOrder);
                    $('.rejectOrder').text(res.rejectOrder);
                    $('.students').text(res.students);
                    $('.teachers').text(res.teachers);
                    $('.admins').text(res.admins);
                    $('.categories').text(res.categories);
                    $('.courses').text(res.courses);
                    $('.assignedCourses').text(res.assigned_courses);
                    $('.notices').text(res.notices);
                }
            });
        }

        // Pre-defined buttons
        $('.filterBtn').click(function(){
            let filter = $(this).data('filter');
            $('#fromDate, #toDate').val('');
            loadDashboard(filter);
        });

        // Custom date range live filter
        $('#fromDate, #toDate').on('change', function(){
            let from = $('#fromDate').val();
            let to = $('#toDate').val();
            if(from && to){
                loadDashboard('custom', from, to);
            }
        });

        // Initial load
        loadDashboard(); 
    });
</script>
@endsection

