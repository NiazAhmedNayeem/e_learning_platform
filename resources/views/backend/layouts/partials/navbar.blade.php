<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <style>
        .notification-badge { min-width: 20px; height: 20px; font-size: 0.65rem; background: linear-gradient(135deg,#ff6b6b,#ff0000); color: #fff; display: flex; align-items:center; justify-content:center; border-radius: 50%; box-shadow: 0 0 4px rgba(255,0,0,0.5); padding: 0 5px; }

    </style>
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ route('dashboard') }}">
        <img src="{{ site_logo_url() }}" alt="logo" height="45" width="100"/>
        {{-- E-Learning --}}
    </a>

    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Right Side -->
    <ul class="navbar-nav ms-auto me-3 me-lg-4">

        {{-- Messages Dropdown --}}
        <li class="nav-item dropdown me-3 mt-2" id="navbar-message-dropdown">
            <a class="nav-link position-relative" href="#" id="messageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #f8f9fa;">
                <i class="fas fa-envelope fa-fw fs-5"></i>
                <span class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill" id="message-count">0</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="messageDropdown" style="min-width: 300px;" id="message-list">
                <li><span class="dropdown-item">Loading...</span></li>
            </ul>
        </li>

        {{-- Cart (Student Only) --}}
        @if(auth()->check() && auth()->user()->role == 'student')
            <li class="nav-item dropdown me-3 mt-2">
                <a class="nav-link position-relative" href="{{ route('frontend.cart') }}" style="color: #f8f9fa;">
                    <i class="fas fa-cart-plus me-2"></i>
                    @php
                        $cartItems = App\Models\Cart::where('user_id', auth()->id())->count();
                    @endphp
                    @if($cartItems > 0)
                        <span class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill">
                            {{ $cartItems }}
                        </span>
                    @endif
                </a>
            </li>
        @endif

        {{-- Notifications Dropdown --}}
        <li class="nav-item dropdown me-3 mt-2">
            <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #f8f9fa;">
                <i class="fas fa-bell fa-fw fs-5"></i>
                @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                    <span class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="min-width: 260px;">
                <li><h6 class="dropdown-header">Notifications</h6></li>
                @forelse(auth()->user()->unreadNotifications as $notification)
                    <li>
                        <a class="dropdown-item py-3 px-3 d-flex justify-content-between align-items-center rounded-3 mb-2 shadow-sm"
                           style="background: linear-gradient(90deg, #f9f9f9, #e9f7ff);"
                           href="{{ $notification->data['url'] ?? route('profile.notifications') }}">
                            <div class="me-3">
                                <div class="fw-semibold text-primary">
                                    {{ Str::limit($notification->data['message'] ?? 'New notification',28) }}
                                </div>
                                <small class="text-muted">
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
            <a class="nav-link d-flex align-items-center" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ auth()->user()->image_show }}" alt="User Image" class="rounded-circle shadow-sm" width="35" height="35">
            </a>

            <ul class="dropdown-menu dropdown-menu-end p-3 shadow-lg" aria-labelledby="navbarDropdown" style="min-width: 260px;">
                <!-- User Info -->
                <div class="text-center mb-3">
                    <img src="{{ auth()->user()->image_show }}" alt="User Image" class="rounded-circle mb-2 shadow-sm" width="80" height="80">
                    <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                    <p class="mb-0 text-muted small">{{ auth()->user()->email }}</p>
                    <p class="bg-success text-white rounded-pill d-inline-block mt-2 px-3">
                        @if(auth()->user()->is_super) Super @endif {{ ucfirst(auth()->user()->role) }}
                    </p>
                </div>

                <li><hr class="dropdown-divider"></li>

                @php
                    $auth = auth()->check();
                    if($auth && auth()->user()->role == 'admin'){ $url='admin.profile'; $dashboardUrl='admin.dashboard'; }
                    elseif($auth && auth()->user()->role=='teacher'){ $url='teacher.profile'; $dashboardUrl='teacher.dashboard'; }
                    elseif($auth && auth()->user()->role=='student'){ $url='student.profile'; $dashboardUrl='student.dashboard'; }
                @endphp

                <li><a class="dropdown-item" href="{{ route($dashboardUrl) }}"><i class="fas fa-home me-2 text-primary"></i> Dashboard</a></li>
                <li><a class="dropdown-item" href="{{ route('messages.index') }}"><i class="fas fa-message me-2 text-info"></i> Messages</a></li>
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



<script>



    function fetchNavbarMessages() {
        $.get("{{ route('navbar.unread') }}", function(data){
            let totalUnread = data.reduce((sum, msg) => sum + msg.unread_count, 0);
            if(totalUnread > 0){
                $("#message-count").text(totalUnread).show();
            } else {
                $("#message-count").hide();
            }

            let html = '';
            if(totalUnread > 0){
                html += '<li><h6 class="dropdown-header">Unread Messages</h6></li>';

                data.forEach(msg => {

                    let displayText = msg.unread_count === 1 ? msg.message : `+${msg.unread_count} new messages`;
                    if(displayText.length > 30){
                        displayText = displayText.substring(0, 30) + '...';
                    }
                    html += `
                    <li>
                        <a class="dropdown-item d-flex align-items-start py-2 px-3 rounded-3 shadow-sm" 
                        href="{{ route('messages.index') }}?user=${msg.sender_id}">
                            <img src="${msg.sender_image}" alt="Sender Image" class="rounded-circle me-2 shadow-sm" width="45" height="45">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <strong>${msg.sender_name}</strong>
                                    ${msg.unread_count > 1 ? `<span class="badge bg-danger rounded-circle" 
                                        style="width:20px; height:20px; font-size:0.7rem; display:flex; align-items:center; justify-content:center;">
                                        ${msg.unread_count}
                                    </span>` : ''}
                                </div>
                                <span class="small text-muted"><i class="fas fa-message me-1"></i>${displayText}</span><br>
                                <small class="text-muted"><i class="fas fa-clock me-1"></i>${msg.created_at}</small>
                            </div>
                        </a>
                    </li>
                    `;
                });
            } else {
                html = '<li><span class="dropdown-item">No unread messages</span></li>';
            }
            $("#message-list").html(html);
        });
    }       



    // Initial fetch
    fetchNavbarMessages();

    // Refresh every 1 seconds
    setInterval(fetchNavbarMessages, 30000);




</script>
