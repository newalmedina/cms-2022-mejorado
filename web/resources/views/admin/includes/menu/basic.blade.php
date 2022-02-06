@if (Auth::user()->isAbleTo('admin-struct'))
    <li class="nav-item has-treeview @if (Request::is('admin/pages*') || Request::is('admin/media*') || Request::is('admin/menu*')) menu-open @endif">
        <a href="#" class="nav-link">
            <i class="nav-icon fa fa-th-large" aria-hidden="true"></i>
            <p>
                {{ trans('general/admin_lang.estructura_web') }}
                <i class="right fas fa-angle-left" aria-hidden="true"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @if (Auth::user()->isAbleTo('admin-menu'))
                <li class="nav-item">
                    <a href="{{ url('/admin/menu') }}"  class="nav-link @if (Request::is('admin/menu*'))  active @endif">
                        <i class="fas fa-bars nav-icon" aria-hidden="true"></i>
                        {{ trans('general/admin_lang.menu') }}
                    </a>
                </li>
            @endif

            @if (Auth::user()->isAbleTo('admin-pages'))
                <li class="nav-item">
                    <a href="{{ url('/admin/pages') }}"  class="nav-link @if (Request::is('admin/pages*')) active @endif">
                        <i class="fas fa-file-alt nav-icon" aria-hidden="true"></i>
                        {{ trans('general/admin_lang.pages') }}
                    </a>
                </li>
            @endif

            @if (Auth::user()->isAbleTo('admin-media'))
                <li class="nav-item">
                    <a href="{{ url('/admin/media') }}"  class="nav-link @if (Request::is('admin/media*')) active @endif">
                        <i class="fas fa-camera nav-icon" aria-hidden="true"></i>
                        {{ trans('general/admin_lang.media') }}
                    </a>
                </li>
            @endif

        </ul>
    </li>
@endif
