@if(Auth::user()->isAbleTo('admin-idiomas') && config('general.multilanguage', false))
    <li class="nav-item">
        <a href="{{ url('/admin/idiomas') }}" class="nav-link @if (Request::is('admin/idiomas*'))) active @endif">
            <i class="fas fa-language" aria-hidden="true"></i> <p>{{ trans('Idiomas::idiomas/admin_lang.idioma') }}</p>
        </a>
    </li>
@endif

