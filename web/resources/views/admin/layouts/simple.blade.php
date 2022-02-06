<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @section('title')
            {{ config('app.name', '') }} ::
        @show
    </title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset("assets/admin/img/favicon.png") }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset("assets/admin/img/favicon.png") }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset("/assets/admin/vendor/fontawesome-free/css/all.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("/assets/admin/css/adminlte.css") }}">

    <!-- App -->
    <link href="{{ asset('/assets/admin/css/app.css') }}" rel="stylesheet" type="text/css">

    @yield('head_page')

</head>

<body class="hold-transition login-page">

    <div id="app">
        @yield('content')
    </div>

    <!-- REQUIRED JS SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ asset("/assets/admin/vendor/jquery/jquery.min.js") }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset("/assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset("/assets/admin/vendor/adminlte/js/adminlte.min.js") }}"></script>

    <!-- App -->
    <script src="{{ asset('/assets/admin/js/app.js') }}" type="text/javascript" defer></script>

    @yield('foot_page')

</body>
</html>
