@if(Auth::user()->isAbleTo('front-products'))
    <li @if (Request::is('front/products*')) class="nav-active" @endif>
        <a class="nav-link" href="{{ url('/front/products') }}">
            <i class="fa fab fa-product-hunt" aria-hidden="true"></i>
            <span>{{ trans('Products::products/front_lang.products') }}</span>
        </a>
    </li>
@endif
