<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Favicon-->
        <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

        <title>SB Admin 2 - Dashboard</title>


        <!-- Custom fonts for this template-->
        <link href="{{ asset('backend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="{{ asset('backend/css/sb-admin-2.min.css') }}" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        @yield('style')
    </head>

    <body id="page-top">

        <div id="app">
            <!-- Page Wrapper -->
            <div id="wrapper">

                <!-- Sidebar -->
                @include('partial.backend.sidebar')

                <!-- End of Sidebar -->

                <!-- Content Wrapper -->
                <div id="content-wrapper" class="d-flex flex-column">

                    <!-- Main Content -->
                    <div id="content">

                        <!-- Topbar -->
                        @include('partial.backend.navbar')

                        <!-- End of Topbar -->

                        <!-- Begin Page Content -->
                        <div class="container-fluid">

                            @yield('content')

                        </div>

                    </div>

                    <!-- Footer -->
                    @include('partial.backend.footer')

                    <!-- End of Footer -->

                    <!-- Scroll to Top Button-->
                    <a class="scroll-to-top rounded" href="#page-top">
                        <i class="fas fa-angle-up"></i>
                    </a>

                    <!-- Logout Modal-->
                    @include('partial.backend.modal')
                </div>
            </div>

        </div>
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        <!-- Core plugin JavaScript-->
        <script src="{{ asset('backend/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

        <!-- Custom scripts for all pages-->
        <script src="{{ asset('backend/js/sb-admin-2.min.js') }}"></script>

        @yield('script')

    </body>

</html>