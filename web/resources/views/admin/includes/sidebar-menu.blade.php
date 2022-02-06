<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
            <a href="{{ url('/admin') }}" class="nav-link @if (Request::is('admin')) active @endif">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    {{ trans('general/admin_lang.dashboard') }}
                </p>
            </a>
        </li>
        @if(Auth::user()->isAbleTo('admin-roles') || Auth::user()->isAbleTo('admin-users'))
        <li class="nav-item has-treeview @if (Request::is('admin/users*') || Request::is('admin/roles*') || Request::is('admin/acceso*')) menu-open @endif">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-users" aria-hidden="true"></i>
                <p>
                    {{ trans('general/admin_lang.users') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @if(Auth::user()->isAbleTo('admin-users'))
                <li class="nav-item">
                    <a href="{{ url('/admin/users') }}" class="nav-link @if (Request::is('admin/users*')) active @endif">
                        <i class="far fa-user nav-icon" aria-hidden="true"></i>
                        <p>{{ trans('general/admin_lang.users') }}</p>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isAbleTo('admin-roles'))
                <li class="nav-item">
                    <a href="{{ url('/admin/roles') }}" class="nav-link @if (Request::is('admin/roles*')) active @endif">
                        <i class="fas fa-key nav-icon" aria-hidden="true"></i>
                        <p>{{ trans('general/admin_lang.roles') }}</p>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isAbleTo('admin-control-acceso'))
                <li class="nav-item">
                    <a href="{{ url('/admin/acceso') }}" class="nav-link @if (Request::is('admin/acceso*')) active @endif">
                        <i class="fas fa-universal-access nav-icon" aria-hidden="true"></i>
                        <p>{{ trans('general/admin_lang.control_acceso') }}</p>
                    </a>
                </li>
                @endif

            </ul>
        </li>

        @endif

        <?php
            // Cargamos el resto de puntos de menu
            $files = new \Illuminate\Filesystem\Filesystem;

            $menuPath = base_path('resources/views/admin/includes/menu/');

            if ($files->isDirectory($menuPath)) {
                foreach ($files->allFiles($menuPath) as $file) {
                    ?>
                    @include('admin.includes.menu.'.basename($file, ".blade.php"))
                    <?php
                }
            }
        ?>



        @if (!config("general.only_backoffice", false))
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link">
                    <i class="fas fa-globe nav-icon" aria-hidden="true"></i>
                    <p>{{ trans('general/admin_lang.frontend') }}</p>
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a href="{{ route('admin.logout') }}" class="nav-link"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"
            >
                <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                <p>{{ trans('general/admin_lang.desconectar') }}</p>
            </a>
        </li>


    </ul>
</nav>
<!-- /.sidebar-menu -->
