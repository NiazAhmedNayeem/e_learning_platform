<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark" 
{{-- @if (auth()->check() && auth()->user()->role == 'admin' && auth()->user()->is_super == 1)
    style="background-color: #212529"
@elseif(auth()->check() && auth()->user()->role == 'admin')
    style="background-color: blue"
@elseif (auth()->check() && auth()->user()->role == 'teacher')
    style="background-color: #294127"
@elseif (auth()->check() && auth()->user()->role == 'student')
    style="background-color: green"                                                                                              
@endif --}}
>
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ route('dashboard') }}">Student Management</a>

    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search-->
    {{-- <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form> --}}

    <!-- Navbar Right Side -->
    <ul class="navbar-nav ms-auto me-3 me-lg-4">

        {{-- Cart --}}
        @if (auth()->user()->role == 'student')
            <li class="nav-item dropdown me-3 mt-2">
                <a class="nav-link position-relative" href="{{ route('frontend.cart') }}" style="color: #f8f9fa;">
                    <i class="fas fa-cart-plus me-2"></i>

                    @php
                        $user = auth()->user()->id;
                        $cartItems = App\Models\Cart::with('course')->where('user_id', $user)->count();
                    @endphp


                    @if(auth()->check() && $cartItems > 0)
                        <span class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill">
                            {{ $cartItems }}
                        </span>
                    @endif
                </a>
            </li>
        @endif
        

        <!-- Notification Icon -->
        <li class="nav-item dropdown me-3 mt-2">
            

            {{-- Notification --}}
            <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #f8f9fa;">
                <i class="fas fa-bell fa-fw fs-5"></i>

                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill">
                        {{ auth()->user()->unreadNotifications->count() }}
                        <span class="visually-hidden">unread notifications</span>
                    </span>
                @endif
            </a>

            <style>
                .notification-badge {
                    min-width: 17px;
                    height: 17px;
                    font-size: 0.65rem;
                    background: linear-gradient(135deg, #ff6b6b, #ff0000);
                    color: #fff;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                    box-shadow: 0 0 6px rgba(255, 0, 0, 0.6);
                }
            </style>

            
            
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="min-width: 260px;">
                <li><h6 class="dropdown-header">Notifications</h6></li>

                @forelse(auth()->user()->unreadNotifications as $notification)
                <li>
                    <a class="dropdown-item py-3 px-3 d-flex justify-content-between align-items-center rounded-3 mb-2 shadow-sm"
                    style="background: linear-gradient(90deg, #f9f9f9, #e9f7ff);"
                    href="{{ $notification->data['url'] ?? route('profile.notifications') }}">
                    
                        <div class="me-3">
                            <div class="fw-semibold text-primary">
                                {{ limitText($notification->data['message'] ?? 'New notification', 28) }}
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                {{ $notification->created_at->setTimezone('Asia/Dhaka')->format('d M Y h:i A') }}
                            </small>
                        </div>

                        @if(!$notification->read_at)
                            <span class="badge rounded-pill px-3 py-2" style="background-color:#ff6b6b; color:white;">New</span>
                        @endif
                    </a>
                </li>
                @empty
                    <li><span class="dropdown-item">No new notifications</span></li>
                @endforelse
            </ul>
        </li>

     

        <!-- User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link  d-flex align-items-center" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ auth()->user()->image_show }}" alt="User Image" class="rounded-circle shadow-sm" width="35" height="35">
            </a>

            <ul class="dropdown-menu dropdown-menu-end p-3 shadow-lg" aria-labelledby="navbarDropdown" style="min-width: 260px;">
                <!-- User Info -->
                <div class="text-center mb-3">
                    <img src="{{ auth()->user()->image_show }}" alt="User Image" class="rounded-circle mb-2 shadow-sm" width="80" height="80">
                    <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                    <p class="mb-0 text-muted small">{{ auth()->user()->email }}</p>

                    @if (auth()->user()->is_super == 1)
                        <p class="bg-success text-white rounded-pill d-inline-block mt-2 px-3">Super {{ ucfirst(auth()->user()->role) }}</p>
                    @else
                        <p class="bg-success text-white rounded-pill d-inline-block mt-2 px-3">{{ ucfirst(auth()->user()->role) }}</p>
                    @endif
                </div>

                <li><hr class="dropdown-divider"></li>

                @php
                    $auth = auth()->check();
                    if($auth && auth()->user()->role == 'admin'){
                        $url = 'admin.profile';
                        $dashboardUrl = 'admin.dashboard';
                    }elseif($auth && auth()->user()->role == 'teacher'){
                        $url = 'teacher.profile';
                        $dashboardUrl = 'teacher.dashboard';
                    }elseif($auth && auth()->user()->role == 'student'){
                        $url = 'student.profile';
                        $dashboardUrl = 'student.dashboard';
                    }
                @endphp

                <li><a class="dropdown-item" href="{{ route($dashboardUrl) }}"><i class="fas fa-home me-2 text-primary"></i> Dashboard</a></li>
                <li><a class="dropdown-item" href="{{ route($url) }}"><i class="fas fa-user me-2 text-success"></i> Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('profile.notifications') }}"><i class="fas fa-bell me-2 text-warning"></i> Notifications</a></li>
                <li><a class="dropdown-item" href="{{ route('password.change.form') }}"><i class="fas fa-lock me-2 text-danger"></i> Change Password</a></li>

                <li><hr class="dropdown-divider"></li>

                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
