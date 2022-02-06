<!-- start: sidebar -->
<aside id="sidebar-left" class="sidebar-left">

    <div class="sidebar-header">
        <div class="sidebar-title">
            {{ trans("general/admin_lang.MENU") }}
        </div>
        <div id="sidebarToggle" class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">

                <ul class="nav nav-main">
                    <li @if (Request::is('dashboard')) class="nav-active" @endif>
                        <a class="nav-link" href="{{ url('/dashboard') }}">
                            <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
                            <span>{{ trans('dashboard/front_lang.dashboard') }}</span>
                        </a>
                    </li>

                    <?php
                    // Cargamos el resto de puntos de menu
                    $files = new \Illuminate\Filesystem\Filesystem();

                    $menuPath = base_path('resources/views/front/includes/menu/');

                    if ($files->isDirectory($menuPath)) {
                        foreach ($files->allFiles($menuPath) as $file) {
                            ?>
                            @include('front.includes.menu.'.basename($file, ".blade.php"))
                            <?php
                        }
                    }
                    ?>

                    @if (auth()->user()->hasRole('admin'))
                        <li>
                            <a class="nav-link" href="{{ url('admin') }}">
                                <i class="fas fa-cogs" aria-hidden="true"></i>
                                <span>{{ trans('dashboard/front_lang.admin') }}</span>
                            </a>
                        </li>
                    @endif

                    <li>
                        <a class="nav-link" href="{{ route('logout')  }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-power-off" aria-hidden="true"></i> <span>
                                {{ trans("general/admin_lang.desconectar") }}</span>
                        </a>
                    </li>

                    @if(session()->has("original-user-suplantar"))
                    <li>
                        <a class="nav-link" href="{{ url("admin/users/suplantar/revertir") }}">
                            <i class="fas fa-user-secret" aria-hidden="true"></i>
                            <span>Revertir al administrador</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>


        </div>

        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');

                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>


    </div>

</aside>
<!-- end: sidebar -->
