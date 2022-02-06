<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="landing simple-sticky-header-enabled">
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
		<link rel="shortcut icon" href="{{ asset("assets/front/img/favicon.ico") }}" type="image/x-icon" />
		<link rel="apple-touch-icon" href="{{ asset("assets/front/img/apple-touch-icon.png") }}">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="{{ asset("/assets/front/vendor/bootstrap/css/bootstrap.css") }}" />
		<link rel="stylesheet" href="{{ asset("/assets/front/vendor/animate/animate.css") }}">
		<link rel="stylesheet" href="{{ asset("/assets/front/vendor/font-awesome/css/all.min.css") }}" />
		<link rel="stylesheet" href="{{ asset("/assets/front/vendor/boxicons/css/boxicons.min.css") }}" />

        <!-- Specific Page Vendor CSS -->
        <link rel="stylesheet" href="{{ asset("/assets/front/vendor/owl.carousel/assets/owl.carousel.css") }}" />
        <link rel="stylesheet" href="{{ asset("/assets/front/vendor/owl.carousel/assets/owl.theme.default.css") }}" />


		<!-- Theme CSS -->
		<link href="{{ asset("/assets/front/css/theme.css") }}" rel="stylesheet" type="text/css" />

        <!-- Theme Custom CSS -->
        <link href="{{ asset("/assets/front/css/app.css") }}" rel="stylesheet" type="text/css" />

       @yield('head_page')

		<!-- Head Libs -->
		<script src="{{ asset("/assets/front/vendor/modernizr/modernizr.js") }}"></script>

	</head>

	<body class="alternative-font-4 loading-overlay-showing" data-plugin-page-transition data-loading-overlay data-plugin-options="{'hideDelay': 100}">
		<div class="loading-overlay">
			<div class="bounce-loader">
				<div class="bounce1"></div>
				<div class="bounce2"></div>
				<div class="bounce3"></div>
			</div>
		</div>

		<div class="body">

            <!-- Header -->
            @include('front.includes.header_home')

            <!-- Sidebar -->
            @include('front.includes.sidebar_home')

			<div role="main" class="main">

                @yield('content')

            </div>

            @include('front.includes.control-sidebar_home')

            <!-- Footer -->
            @include('front.includes.footer_home')

            @include('front.includes.suplantacion')

		</div>

		<!-- Vendor -->
		<script src="{{ asset("/assets/front/vendor/jquery/jquery.js") }}"></script>
		<script src="{{ asset("/assets/front/vendor/jquery-browser-mobile/jquery.browser.mobile.js") }}"></script>
		<script src="{{ asset("/assets/front/vendor/popper/umd/popper.min.js") }}"></script>
		<script src="{{ asset("/assets/front/vendor/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
		<script src="{{ asset("/assets/front/vendor/common/common.js") }}"></script>
		<script src="{{ asset("/assets/front/vendor/nanoscroller/nanoscroller.js") }}"></script>
		<script src="{{ asset("/assets/front/vendor/jquery-placeholder/jquery.placeholder.js") }}"></script>

        <!-- Specific Page Vendor -->
        <script src="{{ asset("/assets/front/vendor/jquery-appear/jquery.appear.js") }}"></script>
        <script src="{{ asset("/assets/front/vendor/owl.carousel/owl.carousel.js") }}"></script>
        <script src="{{ asset("/assets/front/vendor/jquery.lazyload/jquery.lazyload.js") }}"></script>

		<!-- Specific Page -->
        @yield('foot_page')

		<!-- Theme Base, Components and Settings -->
		<script src="{{ asset("/assets/front/js/theme.js") }}"></script>

		<!-- Theme Custom -->
        <script src="{{ asset("/assets/front/js/app.js") }}" type="text/javascript"></script>

		<!-- Theme Initialization Files -->
		<script src="{{ asset("/assets/front/js/theme.init.js") }}"></script>

        <!-- Estadísticas de la web, si usa otro sistema, quitar-->
        @include('front.includes.foot')

        {{-- Cookies --}}
        @include('front.includes.cookies')

	</body>
</html>
