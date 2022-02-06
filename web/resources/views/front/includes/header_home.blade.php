<header id="header" class="header header-nav-links header-nav-menu header-transparent" data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': false, 'stickyEnableOnMobile': true, 'stickyStartAt': 70, 'stickyChangeLogo': false, 'stickyHeaderContainerHeight': 70}">
    <div class="header-body border-top-0 bg-dark box-shadow-none">
        <div class="header-container container h-100">
            <div class="header-row h-100">
                <div class="header-column">
                    <div class="header-row">
                        <div class="header-logo">
                            <a href="{{ url('/') }}" class="goto-top"><img alt="{{ env('APP_NAME') }}" width="102" height="45" data-sticky-width="82" data-sticky-height="36" data-sticky-top="0" src="{{ asset("/assets/front/img/lazy.png") }}" data-plugin-lazyload data-plugin-options="{'threshold': 500}" data-original="{{ asset("/assets/front/img/logo-light.png") }}"></a>
                        </div>
                    </div>
                </div>
                <div class="header-column justify-content-end">
                    <div class="header-row">
                        <button class="btn header-btn-collapse-nav d-lg-none order-3 mt-0 ms-3 me-0" data-bs-toggle="collapse" data-bs-target=".header-nav">
                            <i class="fas fa-bars"></i>
                        </button>
                        @include('front.includes.partials.header_menu')

                        @include('front.includes.partials.header_notifications')

                        @include('front.includes.partials.header_menu_right')
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
