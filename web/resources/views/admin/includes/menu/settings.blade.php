@if(Auth::user()->isAbleTo('admin-settings'))
    <li class="nav-item">
        <a href="{{ url('/admin/settings') }}" class="nav-link @if (Request::is('admin/settings*'))) active @endif">
            <i class="fas fa-cogs" aria-hidden="true"></i> <p>{{ trans('Settings::admin_lang.title') }}</p>
        </a>
    </li>
@endif

