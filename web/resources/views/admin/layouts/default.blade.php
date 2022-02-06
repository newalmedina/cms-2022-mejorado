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

<body class="hold-transition sidebar-mini layout-fixed {{ Session::get('sidebarState', '') }}">

    <div id="app" class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset("assets/admin/img/clavel_c_64.png") }}" alt="{{ env('APP_NAME') }}" height="60" width="60">
        </div>

        <!-- Header -->
        @include('admin.includes.header')

        <!-- Sidebar -->
        @include('admin.includes.sidebar')


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('pre-content')
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">
                                {!! $page_title_icon ?? null !!}
                                {{
                                    $page_title ?
                                    (strlen($page_title)>40?Str::substr($page_title, 0, 40).'...':$page_title) :
                                    "Page Title"
                                }}
                                <small>{!! $page_description ?? null !!}</small>
                            </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="{{ route("admin") }}">
                                        <i class="fas fa-tachometer-alt" aria-hidden="true"></i> {{ trans("general/admin_lang.home") }}
                                    </a>
                                </li>
                                @section('breadcrumb')
                                @show
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">

                    <!-- Your Page Content Here -->
                    @yield('content')
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @include('admin.includes.footer')

        <!-- Sidebar -->
        @include('admin.includes.sidebar-right')

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- REQUIRED JS SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ asset("/assets/admin/vendor/jquery/jquery.min.js") }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset("/assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset("/assets/admin/vendor/adminlte/js/adminlte.min.js") }}"></script>

    <!-- App -->
    <script src="{{ asset('/assets/admin/js/app.js') }}" type="text/javascript" defer></script>

    <script>
        $(document).ready(function () {
            $('#sidebarToggle').on('click', function() {
                $.ajax({
                    url: "{{ url('admin/dashboard/savestate') }}",
                    type: "POST",
                    "headers": {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                    success : function(data) {
                        return false;
                    }
                });
            });

            // Add the change skin listener
            $('[data-skin]').on('click', function (e) {
                e.preventDefault()

                var skinData = JSON.stringify($(this).data('skin-info'));
                var data = '{ "skin" : "' + $(this).data('skin') + '",' +
                    ' "data": ' + skinData +
                    '}';

                saveSkin(data)


            })
        })

        function saveSkin(skin) {

            $.ajax({
                url: "{{ url('admin/dashboard/changeskin') }}",
                type: "POST",
                "headers": {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                data: {
                    skin: skin
                },
                success : function(data) {
                    return false;
                }
            });

        }
    </script>



    @yield('foot_page')

</body>
</html>
