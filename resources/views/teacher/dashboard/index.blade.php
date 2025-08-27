@extends('backend.layouts.master')
@section('title', 'Teacher Dashboard')
@section('main-content')
    
@if (auth()->check() && auth()->user()->status == 1)
    <h1 class="mt-4">Teacher Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Primary Card</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
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
                        Your account is currently under review. Please wait for approval.  
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