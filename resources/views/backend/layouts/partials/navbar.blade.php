<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ route('dashboard') }}">Student Management</a>

    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    <!-- Navbar Right Side -->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">

        <!-- Notification Icon -->
        <li class="nav-item dropdown me-3 mt-2">
            <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <style>
                    .notification-badge {
                    width: 18px;       
                    height: 18px;      
                    font-size: 0.7rem; 
                    padding: 0;        
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                </style>
                <span class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ auth()->user()->unreadNotifications->count() ?? 0 }}
                    <span class="visually-hidden">unread notifications</span>
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                <li><h6 class="dropdown-header">Notifications</h6></li>

                @forelse(auth()->user()->unreadNotifications as $notification)
                <li>
                    <a class="dropdown-item d-flex justify-content-between align-items-start" 
                    href="{{ $notification->data['url'] ?? route('profile.notifications') }}">
                    
                        <div>
                            {{ limitText($notification->data['message'] ?? 'New notification', 30) }}
                            <br>
                            <small class="text-muted">
                                {{ $notification->created_at->setTimezone('Asia/Dhaka')->format('d M Y h:i A') }}
                                {{-- {{ $notification->created_at->timezone(auth()->user()->timezone ?? config('app.timezone'))
                                    ->format('d M Y h:i A') }} --}}
                            </small>
                        </div>

                        @if(!$notification->read_at)
                            <span class="badge bg-primary rounded-pill">New</span>
                        @endif
                    </a>
                </li>
                @empty
                    <li><span class="dropdown-item">No new notifications</span></li>
                @endforelse
            </ul>
        </li>

        <!-- User Dropdown -->
        {{-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ auth()->user()->image_show ?? 'https://via.placeholder.com/35' }}" 
                     alt="User Image" 
                     class="rounded-circle" 
                     width="35" 
                     height="35">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#!">Settings</a></li>
                <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
        </li> --}}


        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ auth()->user()->image_show }}" 
                    alt="User Image" 
                    class="rounded-circle" 
                    width="35" 
                    height="35">
            </a>

            <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="navbarDropdown" style="min-width: 250px;">
                <!-- User Info Centered -->
                <div class="text-center mb-3">
                    <img src="{{ auth()->user()->image_show }}" 
                        alt="User Image" 
                        class="rounded-circle mb-2" 
                        width="100" 
                        height="100">
                    <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                    <p class="mb-0 text-muted">{{ auth()->user()->email }}</p>

                    @if (auth()->user()->is_super == 1)
                        <p class="bg-success text-white rounded-pill d-inline-block mt-2 px-3">Super {{ ucfirst(auth()->user()->role) }}</p>
                    @else
                        <p class="bg-success text-white rounded-pill d-inline-block mt-2 px-3">{{ ucfirst(auth()->user()->role) }}</p>
                    @endif
                </div>

                <li><hr class="dropdown-divider"></li>

                    <!-- Links -->
                    @php
                        $auth = auth()->check();
                        if($auth && auth()->user()->role == 'admin'){
                            $url = 'admin.profile';
                        }elseif($auth && auth()->user()->role == 'teacher'){
                            $url = 'teacher.profile';
                        }elseif($auth && auth()->user()->role == 'student'){
                            $url = 'student.profile';
                        }
                        if($auth && auth()->user()->role == 'admin'){
                            $dashboardUrl = 'admin.dashboard';
                        }elseif($auth && auth()->user()->role == 'teacher'){
                            $dashboardUrl = 'teacher.dashboard';
                        }elseif($auth && auth()->user()->role == 'student'){
                            $dashboardUrl = 'student.dashboard';
                        }
                    @endphp
                    <li><a class="dropdown-item" href="{{ route($dashboardUrl) }}">Dashboard</a></li>
                    <li><a class="dropdown-item" href="{{ route($url) }}">Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('profile.notifications') }}">Notifications</a></li>
                    <li><a class="dropdown-item" href="{{ route('password.change.form') }}">Change Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">Logout</button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
