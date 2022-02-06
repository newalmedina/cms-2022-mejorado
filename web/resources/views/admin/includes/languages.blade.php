@if (config('general.multilanguage', false))
    <li class="nav-item dropdown tasks-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-language"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg">
            <span class="dropdown-header">
                <h5><i class="fas fa-language"></i> {{ trans("general/admin_lang.selecciona_idioma") }}</h5>
            </span>
            <li>
                @foreach (App\Models\Idioma::active()->get() as $idioma)
                    <a href="/changelanguage/{{$idioma->code}}" class="dropdown-item">
                        @if($idioma->code==App::getLocale())
                            <i class="far fa-dot-circle text-green"></i>
                        @else
                            <i class="far fa-circle text-gray"></i>
                        @endif
                        <span class="float-right text-muted text-sm">{{$idioma->locale_name}}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                @endforeach

            </li>
        </ul>
    </li>

@endif
