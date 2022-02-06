<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="fixed {{ Session::get('sidebarState', '') }} ">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>
            @section('title')
                {{ config('app.name', '') }} ::
            @show
        </title>
		<meta name="keywords" content="" />
		<meta name="description" content="">
        <meta name="author" content="">
        @section('metas')
            <meta name="title" content="{!! config('app.name', '') !!}">
            <meta name="description" content="{!! config('app.name', '') !!}">
        @show

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset("assets/admin/img/") }}/favicon.png" type="image/x-icon" />
        <link rel="apple-touch-icon" href="{{ asset("assets/admin/img/") }}/favicon.png">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="{{ asset("/assets/front/vendor/bootstrap/css/bootstrap.css") }}" />
		<link rel="stylesheet" href="{{ asset("/assets/front/vendor/animate/animate.css") }}">
		<link rel="stylesheet" href="{{ asset("/assets/front/vendor/font-awesome/css/all.min.css") }}" />
		<link rel="stylesheet" href="{{ asset("/assets/front/vendor/boxicons/css/boxicons.min.css") }}" />
		<link rel="stylesheet" href="{{ asset("/assets/front/vendor/jquery-ui/jquery-ui.css") }}" />
		<link rel="stylesheet" href="{{ asset("/assets/front/vendor/jquery-ui/jquery-ui.theme.css") }}" />


		<!-- Theme CSS -->
		<link href="{{ asset("/assets/front/css/theme.css") }}" rel="stylesheet" type="text/css" />

        <!-- Theme Custom CSS -->
        <link href="{{ asset("/assets/front/css/app.css") }}" rel="stylesheet" type="text/css" />

        @yield('head_page')

		<!-- Head Libs -->
		<script src="{{ asset("/assets/front/vendor/modernizr/modernizr.js") }}"></script>

	</head>
	<body>
		<section class="body">

            <!-- Header -->
            @include('front.includes.header')

			<div class="inner-wrapper">

                <!-- Sidebar -->
                @include('front.includes.sidebar')

				<section role="main" class="content-body">
                    @yield('pre-content')


					<header class="page-header">
						<h2>
                            {!! $page_title_icon ?? null !!}
                            {{ $page_title ?? "Page Title" }}
                            <small>{!! $page_description ?? null !!}</small>
                        </h2>

						<div class="right-wrapper text-end">
							<ol class="breadcrumbs mr-4">
                                <li><a href="{{ route("dashboard") }}"><i class="fas fa-home" aria-hidden="true"></i></a></li>
                                @section('breadcrumb')
                                @show
							</ol>

                            <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
						</div>
					</header>

                    <!-- start: page -->
                    <!-- Page Content -->
                    @yield('content')
					<!-- end: page -->
                </section>


			</div>

            @include('front.includes.control-sidebar')


            <!-- Footer -->
            @include('front.includes.footer')

        </section>



		<!-- Vendor -->
		<script src="{{ asset("/assets/front/vendor/jquery/jquery.js") }}"></script>
		<script src="{{ asset("/assets/front/vendor/jquery-browser-mobile/jquery.browser.mobile.js") }}"></script>
		<script src="{{ asset("/assets/front/vendor/popper/umd/popper.min.js") }}"></script>
		<script src="{{ asset("/assets/front/vendor/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
		<script src="{{ asset("/assets/front/vendor/common/common.js") }}"></script>
		<script src="{{ asset("/assets/front/vendor/nanoscroller/nanoscroller.js") }}"></script>
		<script src="{{ asset("/assets/front/vendor/jquery-placeholder/jquery.placeholder.js") }}"></script>

		<!-- Specific Page Vendor -->
        <script src="{{ asset("/assets/front/vendor/jquery-ui/jquery-ui.js") }}"></script>
        <script src="{{ asset("/assets/front/vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js") }}"></script>
        <script src="{{ asset("/assets/front/vendor/jquery-appear/jquery.appear.js") }}"></script>



        @yield('foot_page')

		<!-- Theme Base, Components and Settings -->
		<script src="{{ asset("/assets/front/js/theme.js") }}"></script>

		<!-- Theme Custom -->
        <script src="{{ asset("/assets/front/js/app.js") }}" type="text/javascript"></script>

		<!-- Theme Initialization Files -->
		<script src="{{ asset("/assets/front/js/theme.init.js") }}"></script>

        <script>
            $(document).ready(function () {

                $('#sidebarToggle').on('click', function() {
                    $.ajax({
                        url: "{{ url('dashboard/savestate') }}",
                        type: "POST",
                        "headers": {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                        success : function(data) {
                            return false;
                        }
                    });
                });

            })
        </script>

        <!-- Estadísticas de la web, si usa otro sistema, quitar-->
        @include('front.includes.foot')

        {{-- Cookies --}}
        @include('front.includes.cookies')


        @if(!is_null(auth()->user()) && auth()->user()->isAbleTo('frontend-centros-change'))
            @include('front.includes.hidden_divs')
        @endif

	</body>
</html>
