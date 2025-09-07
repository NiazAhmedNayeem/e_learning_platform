<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion"
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
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>

        

    {{-- Admin Dashboard sidebar start here --}}
                @if(auth()->check() && auth()->user()->role == 'admin' && auth()->user()->status == 1)
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    
                    

                
                        <div class="sb-sidenav-menu-heading">Interface</div>
                    {{-- Add Order --}}
                        <a class="nav-link {{ request()->routeIs('admin.order.index') ? 'active' : '' }}" href="{{ route('admin.order.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                            Orders
                        </a>


                    {{-- Add Category --}}
                        <a class="nav-link {{ request()->routeIs('admin.category.index') ? 'active' : '' }}" 
                        href="{{ route('admin.category.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                            Category
                        </a>


                    {{-- Add course --}}
                        <a class="nav-link {{ request()->routeIs('admin.course.index') ? 'active' : '' }}" href="{{ route('admin.course.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-book-open"></i></div>
                            Courses
                        </a>

                    {{-- Add course --}}
                        <a class="nav-link {{ request()->routeIs('admin.course_assign.index') ? 'active' : '' }}" href="{{ route('admin.course_assign.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-book-open-reader"></i></div>
                            Assign Courses
                        </a>

                    {{-- Add students --}}
                        <a class="nav-link {{ request()->routeIs('admin.student.index') ? 'active' : '' }}" href="{{ route('admin.student.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-user-graduate"></i></div>
                            Students
                        </a>

                    {{-- Add Teachers --}}
                        <a class="nav-link {{ request()->routeIs('admin.all-teacher') ? 'active' : '' }}" href="{{ route('admin.all-teacher') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-user-tie"></i></div>
                            Teachers
                        </a>

                    {{-- Add Admin --}}
                    @if (auth()->user()->is_super == 1)
                        <a class="nav-link {{ request()->routeIs('user.admin.index') ? 'active' : '' }}" href="{{ route('user.admin.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-user-gear"></i></div>
                            Admin
                        </a>
                    @endif
                        
                    {{-- Add User --}}
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            All Users
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="#">Admin</a>
                                <a class="nav-link" href="#">Teachers</a>
                                <a class="nav-link" href="#">Students</a>
                                <a class="nav-link" href="#">Parents</a>
                            </nav>
                        </div>
                


                @endif
    {{-- Admin Dashboard sidebar end here --}}



    {{-- Teacher Dashboard sidebar start here --}}
                @if(auth()->check() && auth()->user()->role == 'teacher' && auth()->user()->status == 1)
                    <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    
                    

                
                    <div class="sb-sidenav-menu-heading">Interface</div>

                    <a class="nav-link {{ request()->routeIs('teacher.profile') ? 'active' : '' }}" href="{{ route('teacher.profile') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-address-card"></i></div>
                        Profile
                    </a>

                    <a class="nav-link {{ request()->routeIs('profile.notifications') ? 'active' : '' }}" href="{{ route('profile.notifications') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-bell"></i></div>
                        Notifications
                    </a>

                    <a class="nav-link {{ request()->routeIs('teacher.assign.courses') ? 'active' : '' }}" href="{{ route('teacher.assign.courses') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-book-open-reader"></i></div>
                        Assign Course
                    </a>

                    <a class="nav-link {{ request()->routeIs('teacher.course.student') ? 'active' : '' }}" href="{{ route('teacher.course.student') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-user-graduate"></i></div>
                        Students
                    </a>

                @endif
    {{-- Teacher Dashboard sidebar end here --}}



    {{-- Student Dashboard sidebar start here --}}
                @if(auth()->check() && auth()->user()->role == 'student' && auth()->user()->status == 1)
                    <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    
                    

                
                    <div class="sb-sidenav-menu-heading">Interface</div>

                    <a class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}" href="{{ route('student.profile') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-address-card"></i></div>
                        Profile
                    </a>

                    <a class="nav-link {{ request()->routeIs('student.courses') ? 'active' : '' }}" href="{{ route('student.courses') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-book-open"></i></div>
                        My Course
                    </a>

                    <a class="nav-link {{ request()->routeIs('student.course.order') ? 'active' : '' }}" href="{{ route('student.course.order') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                        My Orders
                    </a>

                @endif
                
    {{-- Student Dashboard sidebar end here --}}

                @php
                    $auth = auth()->check();
                    if($auth && auth()->user()->role == 'admin'){
                        $dashboardUrl = 'admin.dashboard';
                    }elseif($auth && auth()->user()->role == 'teacher'){
                        $dashboardUrl = 'teacher.dashboard';
                    }elseif($auth && auth()->user()->role == 'student'){
                        $dashboardUrl = 'student.dashboard';
                    }
                @endphp

                @if (auth()->check() && auth()->user()->status == 0 || auth()->user()->status == 2 || auth()->user()->status == 3)
                        <a class="nav-link {{ request()->routeIs($dashboardUrl) ? 'active' : '' }}" href="{{ route($dashboardUrl) }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                @endif

            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            Start Bootstrap
        </div>
    </nav>
</div>