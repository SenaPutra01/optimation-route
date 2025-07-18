<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Corona Admin</title>

    @vite([
    'resources/assets/vendors/mdi/css/materialdesignicons.min.css',
    'resources/assets/vendors/css/vendor.bundle.base.css',
    'resources/assets/css/style.css',
    'resources/assets/vendors/jvectormap/jquery-jvectormap.css',
    'resources/assets/vendors/flag-icon-css/css/flag-icon.min.css',
    ])

    <link rel="shortcut icon" href="{{ Vite::asset('resources/assets/images/favicon.png') }}" />

    @stack('styles')
</head>

<body>
    <div class="container-scroller">
        <!-- partial:../../partials/_sidebar.html -->
        @include('layouts.sidebar')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:../../partials/_navbar.html -->
            @include('layouts.navbar')
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:../../partials/_footer.html -->
                {{-- @include('layouts.footer') --}}
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    <script src="{{ Vite::asset('resources/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ Vite::asset('resources/assets/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>

    @vite([
    'resources/assets/js/off-canvas.js',
    'resources/assets/js/hoverable-collapse.js',
    'resources/assets/js/misc.js',
    'resources/assets/js/settings.js',
    'resources/assets/js/todolist.js',
    'resources/js/app.js',
    'resources/assets/vendors/chart.js/Chart.min.js',
    'resources/assets/vendors/progressbar.js/progressbar.min.js',
    // 'resources/assets/vendors/jvectormap/jquery-jvectormap.min.js',
    'resources/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js',
    'resources/assets/js/off-canvas.js',
    'resources/assets/js/hoverable-collapse.js',
    'resources/assets/js/misc.js',
    'resources/assets/js/settings.js',
    // 'resources/assets/js/dashboard.js',
    ])

    @stack('script');

</body>

</html>