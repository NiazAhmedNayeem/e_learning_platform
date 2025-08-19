<!DOCTYPE html>
<html lang="en">
    
    <!-- Header -->
    <head>
        @include('backend.layouts.partials.header')
    </head>

    <body class="sb-nav-fixed">


        <!-- Navbar -->
        @include('backend.layouts.partials.navbar')


        <div id="layoutSidenav">

            <!-- Sidebar -->
            @include('backend.layouts.partials.sidebar')


            
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        @yield('main-content')
                    </div>
                </main>
                
                <!-- Footer -->
                @include('backend.layouts.partials.footer')

            </div>
        </div>
       {{-- Script section --}}
        @include('backend.layouts.partials.script')
        @yield('scripts')

        

    </body>
</html>
