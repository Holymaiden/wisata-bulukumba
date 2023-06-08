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
        <title>Bulukumba - @stack('title')</title>

        @stack('cssScript')
</head>

<body>
        <!-- Loader starts-->
        <div class="loader-wrapper">
                <div class="loader"></div>
        </div>
        <!-- Loader ends-->
        <!-- login page start-->
        <div class="container-fluid p-0">
                <div class="row m-0">
                        <div class="col-12 p-0">
                                <div class="login-card">
                                        <!-- Content -->
                                        @yield('content')
                                        <!-- Content Ends-->
                                </div>
                        </div>
                </div>
                <!-- Js -->
                @stack('jsScript')
        </div>
</body>

</html>