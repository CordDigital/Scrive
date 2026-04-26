<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ZIZI / Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-light-layout/style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />

    <style>
        .sidebar {
            overflow-y: hidden;
            height: 100vh;
        }

        .sidebar:hover {
            overflow-y: auto;
        }


        .page-body-wrapper {
            height: 100vh;
            overflow-y: auto;
        }

        body {
            overflow: hidden;
        }

        /* Bigger sidebar font */
        .sidebar .nav .nav-item .nav-link .menu-title {
            font-size: 0.975rem;
        }
        .sidebar .nav .nav-item.nav-category .nav-link {
            font-size: 0.8rem;
        }
        .sidebar .nav.sub-menu .nav-item .nav-link {
            font-size: 0.9rem;
        }
        /* Show icons in sub-menu items */
        .sidebar .nav.sub-menu .nav-item .nav-link:before {
            display: none;
        }
        .sidebar .nav.sub-menu .nav-item .nav-link .mdi {
            font-size: 1rem;
            vertical-align: middle;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-scroller">

        @include('admin.layouts.sidebar')
        <!-- partial -->

        <div class="container-fluid page-body-wrapper">
            @include('admin.layouts.navbar')
            <div class="main-panel" style="display:flex; flex-direction:column; min-height:calc(100vh - 63px);">
               <div style="flex:1;">@yield('content')</div>
                @include('admin.layouts.footer')
            </div>
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot/jquery.flot.categories.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot/jquery.flot.fillbetween.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <!-- endinject -->

    <!-- Custom js for this page -->
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/proBanner.js') }}"></script>
    <!-- End custom js for this page -->
    @stack('scripts')
</body>
</html>
