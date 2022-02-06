<li class="nav-item dropdown user user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        @if(!empty(Auth::user()->userProfile->photo))
            <img src="{{ url('admin/profile/getphoto/'.Auth::user()->userProfile->photo) }}" class="user-image" alt="{{ Auth::user()->userProfile->fullname }}">
        @else
            <img src="{{ asset("/assets/admin/img/user.png") }}" class="user-image" alt="{{ Auth::user()->userProfile->fullname }}" />
        @endif
        <span class="hidden-xs">{{ Auth::user()->userProfile->fullname }}</span>
    </a>
    <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">
            @if(!empty(Auth::user()->userProfile->photo))
                <img src="{{ url('admin/profile/getphoto/'.Auth::user()->userProfile->photo) }}" class="img-circle" alt="User Image">
            @else
                <img src="{{ asset("/assets/admin/img/user.png") }}" class="img-circle" alt="{{ Auth::user()->userProfile->fullname }}" />
            @endif
            <p>
                {{ Auth::user()->userProfile->fullname }}
                <small>{{ trans('general/admin_lang.miembro_desde') }} {{ Auth::user()->created_at_formatted }}</small>
            </p>
        </li>
        <!-- Menu Footer-->
        <li class="user-footer">

            <a href="{{ url('admin/profile') }}" class="btn btn-info">{{ trans('general/admin_lang.perfil') }}</a>

            <a href="{{ route('admin.logout') }}" class="btn btn-secondary float-right"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">{{ trans('general/admin_lang.desconectar') }}</a>

        </li>
    </ul>
</li>
