<div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>

                   

            {{-- Admin Dashboard sidebar start here --}}
                            @if(auth()->check() && auth()->user()->role == 'admin' && auth()->user()->status == 1)
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Dashboard
                                </a>
                                
                                
            
                            
                                    <div class="sb-sidenav-menu-heading">Interface</div>

                                {{-- Add Category --}}
                                    <a class="nav-link" href="{{ route('admin.category.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                        Category
                                    </a>

                                {{-- Add course --}}
                                    <a class="nav-link" href="{{ route('admin.course.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                        Courses
                                    </a>

                                {{-- Add students --}}
                                    <a class="nav-link" href="{{ route('admin.student.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                        Students
                                    </a>

                                {{-- Add Teachers --}}
                                    <a class="nav-link" href="{{ route('admin.all-teacher') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                        Teachers
                                    </a>

                                {{-- Add Admin --}}
                                    <a class="nav-link" href="{{ route('user.admin.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                        Admin
                                    </a>

                                    
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
                                <a class="nav-link" href="{{ route('teacher.dashboard') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Dashboard
                                </a>
                                
                                
            
                            
                                <div class="sb-sidenav-menu-heading">Interface</div>

                                <a class="nav-link" href="{{ route('teacher.profile') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Profile
                                </a>

                                <a class="nav-link" href="#">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Assign Course
                                </a>

                                <a class="nav-link" href="#">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Students
                                </a>

                            @endif
            {{-- Teacher Dashboard sidebar end here --}}


            
            {{-- Student Dashboard sidebar start here --}}
                            @if(auth()->check() && auth()->user()->role == 'student' && auth()->user()->status == 1)
                                <a class="nav-link" href="{{ route('student.dashboard') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Dashboard
                                </a>
                                
                                
            
                            
                                <div class="sb-sidenav-menu-heading">Interface</div>

                                <a class="nav-link" href="{{ route('student.profile') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Profile
                                </a>

                                <a class="nav-link" href="#">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    My Course
                                </a>


                            @endif
            {{-- Student Dashboard sidebar end here --}}

                            

                            @if (auth()->check() && auth()->user()->status == 0)
                                 <a class="nav-link" href="{{ route('inactive.dashboard') }}">
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