<!-- start: header -->
<header class="header">
    <div class="logo-container">
        <a href="{{ route('dashboard') }}" class="logo">
            <img src="{{ asset('/assets/front/img/logo.svg') }}" width="120" alt="{{ env('APP_NAME') }}" />
        </a>
        <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html"
            data-fire-event="sidebar-left-opened">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <!-- start: search & user box -->
    <div class="header-right">

        @include('front.includes.notifications')

        <span class="separator"></span>

        {{-- @if (config('general.multilanguage', false))
            <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-language" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu">
                    <li class="header"><h5><i class="fa fa-language" aria-hidden="true"></i> {{ trans("general/admin_lang.selecciona_idioma") }}</h5></li>
                    <li>
                        <!-- inner menu: contains the actual data -->
                        <ul class="menu">
                            @foreach (App\Models\Idioma::active()->get() as $idioma)
                                <li>
                                    <a href="/changelanguage/{{$idioma->code}}">
                                        @if ($idioma->code == App::getLocale())
                                            <i class="fa fa-dot-circle-o text-green" aria-hidden="true"></i>
                                        @else
                                            <i class="fa fa-circle-o text-gray" aria-hidden="true"></i>
                                        @endif
                                        {{ $idioma->locale_name }}
                                    </a>
                                </li>
                            @endforeach
                            <!-- end task item -->
                        </ul>
                    </li>
                </ul>
            </li>
        @endif
        <span class="separator"></span> --}}

        <div id="userbox" class="userbox">
            <a href="#" data-bs-toggle="dropdown">
                <figure class="profile-picture">

                    @if (!empty(Auth::user()->userProfile->photo))
                        <img src="{{ url('profile/getphoto/' . Auth::user()->userProfile->photo) }}"
                            alt="{{ Auth::user()->userProfile->fullName }}" class="rounded-circle"
                            data-lock-picture="{{ url('profile/getphoto/' . Auth::user()->userProfile->photo) }}" />
                    @else
                        <img src="{{ asset('/assets/front/img/!logged-user.jpg') }}"
                            alt="{{ Auth::user()->userProfile->fullName }}" class="rounded-circle"
                            data-lock-picture="{{ asset('/assets/front/img/!logged-user.jpg') }}" />
                    @endif
                </figure>
                <div class="profile-info" data-lock-name="{{ Auth::user()->userProfile->fullName }}"
                    data-lock-email="{{ Auth::user()->email }}">
                    <span class="name">{{ Auth::user()->userProfile->fullName }}</span>
                    <span
                        class="role">{{ implode(
                            ', ',
                            Auth::user()->roles->pluck('display_name')->toArray(),
                        ) }}</span>
                </div>

                <i class="fa custom-caret"></i>
            </a>

            <div class="dropdown-menu">
                <ul class="list-unstyled mb-2">
                    <li class="divider"></li>
                    <li>
                        <a role="menuitem" tabindex="-1" href="{{ url('profile') }}"><i class="fas fa-user"></i>
                            {{ trans('general/admin_lang.perfil') }}</a>
                    </li>
                    @if(!is_null(auth()->user()) && auth()->user()->isAbleTo('frontend-centros-change'))
                        <li>
                            <a role="menuitem" tabindex="-1" href="" id="btnChangeCenter"><i class="fas fa-building"></i>
                                {{ trans('general/front_lang.change_center') }}</a>
                        </li>
                    @endif
                    <li>
                        <a role="menuitem" tabindex="-1" href="{{ route('admin.logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-power-off"></i> {{ trans('general/admin_lang.desconectar') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- end: search & user box -->
</header>
<!-- end: header -->

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
