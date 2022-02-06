@if (Auth::user()->isAbleTo('admin-countries') ||
     Auth::user()->isAbleTo('admin-ccaas') ||
     Auth::user()->isAbleTo('admin-provinces') ||
     Auth::user()->isAbleTo('admin-cities') ||
     Auth::user()->isAbleTo('admin-centers')
     )

    <li class="nav-item has-treeview @if (Request::is('admin/countries*') ||
                Request::is('admin/ccaas*') ||
                Request::is('admin/provinces*') ||
                Request::is('admin/cities*') ||
                Request::is('admin/centers*')
                ) menu-open @endif">
        <a href="#" class="nav-link">
            <i class="nav-icon far fa-map" aria-hidden="true"></i>
            <p>
                {{ trans('locations::locations/admin_lang.localizaciones') }}
                <i class="right fas fa-angle-left" aria-hidden="true"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">


            @if (Auth::user()->isAbleTo('admin-countries'))
                <li class="nav-item">
                    <a href="{{ url('/admin/countries') }}" class="nav-link @if (Request::is('admin/countries*')) active @endif">
                        <i class="fa fas fa-globe-americas" aria-hidden="true"></i>
                        <p>{{ trans('locations::countries/admin_lang.country') }}</p>
                    </a>
                </li>
            @endif

            @if (Auth::user()->isAbleTo('admin-ccaas'))
                <li class="nav-item">
                    <a href="{{ url('/admin/ccaas') }}" class="nav-link @if (Request::is('admin/ccaas*')) active @endif">
                        <i class="fa fas fa-map-marked-alt" aria-hidden="true"></i>
                        <p>{{ trans('locations::ccaas/admin_lang.ccaa') }}</p>
                    </a>
                </li>
            @endif

            @if(Auth::user()->isAbleTo('admin-provinces'))
                <li class="nav-item">
                    <a href="{{ url('/admin/provinces') }}" class="nav-link @if (Request::is('admin/provinces*')) active @endif">
                        <i class="fa fas fa-map-marked-alt" aria-hidden="true"></i>
                        <p>{{ trans('locations::provinces/admin_lang.province') }}</p>
                    </a>
                </li>
            @endif


            @if (Auth::user()->isAbleTo('admin-cities'))
                <li class="nav-item">
                    <a href="{{ url('/admin/cities') }}" class="nav-link @if (Request::is('admin/cities*')) active @endif">
                        <i class="fa fas fa-map-marker-alt" aria-hidden="true"></i>
                        <p>{{ trans('locations::cities/admin_lang.city') }}</p>
                    </a>
                </li>
            @endif

            @if(Auth::user()->isAbleTo('admin-centers'))
                <li class="nav-item">
                    <a href="{{ url('/admin/centers') }}" class="nav-link @if (Request::is('admin/centers*')) active @endif">
                        <i class="fa fas fa-hospital" aria-hidden="true"></i> <p>{{ trans('Centers::centers/admin_lang.center') }}</p>
                    </a>
                </li>
            @endif



        </ul>
    </li>
@endif
