@if(Auth::user()->isAbleTo('admin-crud-generator'))
<li class="nav-item has-treeview @if (Request::is('admin/crud-generator*')) menu-open @endif">
    <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cubes" aria-hidden="true"></i>
            <p>
                {{ trans('crud-generator::general/admin_lang.title') }}
                <i class="right fas fa-angle-left" aria-hidden="true"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @if(Auth::user()->isAbleTo('admin-modulos-crud'))
                <li class="nav-item">
                    <a href="{{ url('/admin/crud-generator') }}" class="nav-link @if (Request::is('admin/crud-generator*')) active @endif">
                        <i class="fa fa-cube" aria-hidden="true"></i>
                        {{ trans('crud-generator::modules/admin_lang.title') }}
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
