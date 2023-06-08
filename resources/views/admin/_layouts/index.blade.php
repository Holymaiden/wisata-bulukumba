<!DOCTYPE html>
<html lang="en">

<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Fihaa Dashboard.">
        <meta name="keywords" content="Fihaa Dashboard">
        <meta name="author" content="pixelstrap">
        <link rel="icon" href="{{ url('assets/logo/logo.png') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ url('assets/logo/logo.png') }}" type="image/x-icon">
        <title>Bulukumba - Dashboard</title>

        @stack('cssScript')
</head>

<body>
        <!-- tap on top starts-->
        <div class="tap-top"><i data-feather="chevrons-up"></i></div>
        <!-- tap on tap ends-->
        <!-- Loader starts-->
        <div class="loader-wrapper">
                <div class="loader"></div>
        </div>
        <!-- Loader ends-->
        <!-- page-wrapper Start-->
        <div class="page-wrapper compact-wrapper" id="pageWrapper">
                <!-- Page Header Start-->
                @include('admin._layouts.header')
                <!-- Page Header Ends-->
                <!-- Page Body Start-->
                <div class="page-body-wrapper">
                        <!-- Page Sidebar Start-->
                        @include('admin._layouts.sidebar')
                        <!-- Page Sidebar Ends-->
                        <!-- Content -->
                        @yield('content')
                        <!-- Content Ends-->
                        <!-- Footer start-->
                        @include('admin._layouts.footer')
                        <!-- Footer Ends-->
                </div>
        </div>
        <!-- Js -->
        @stack('jsScript')

        @stack('jsScriptAjax')
</body>

</html>