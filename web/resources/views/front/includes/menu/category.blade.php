@if(Auth::user()->isAbleTo('front-categories'))
    <li @if (Request::is('front/categories*')) class="nav-active" @endif>
        <a class="nav-link" href="{{ url('/front/categories') }}">
            <i class="fa fas fa-th-list" aria-hidden="true"></i>
            <span>{{ trans('Categories::categories/front_lang.categories') }}</span>
        </a>
    </li>
@endif
