<!-- Navbar -->
<nav class="main-header navbar navbar-expand {{ Skin::get('header') }} ">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a id="sidebarToggle" class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

        <!-- Menus adicionales -->
        @yield('additional_menu')
    </ul>

    <!-- Buscador u otras opciones -->
    @yield('search_menu')

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        {{-- @include('admin.includes.messages') --}}

        @include('admin.includes.notifications')

        @include('admin.includes.languages')

        @include('admin.includes.user-data-menu')

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>

    </ul>

    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</nav>
<!-- /.navbar -->
