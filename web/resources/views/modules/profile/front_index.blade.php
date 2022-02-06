@extends('front.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('breadcrumb')

<li><span>{{ $page_title }}</span></li>

@stop

@section('head_page')
    @yield('tab_head')
@stop

@section('content')

    <!-- start: page -->
    @include('front.includes.errors')
    @include('front.includes.success')

    <div class="row">
        <div class="col-lg-4 col-xl-3 mb-4 mb-xl-0">

            <section class="card">
                <div class="card-body">
                    <div class="thumb-info mb-3">
                        <div id="fileOutput">
                            @if($user->userProfile->photo!='')
                                <img src='{{ url('profile/getphoto/'.$user->userProfile->photo) }}' id='image_ouptup' class="rounded img-fluid" alt="{{ Auth::user()->userProfile->fullName }}">
                            @else
                                <img src="{{ asset("/assets/front/img/!logged-user.jpg") }}" class="rounded img-fluid" alt="{{ Auth::user()->userProfile->fullName }}">
                            @endif
                        </div>


                        <div class="thumb-info-title">
                            <span class="thumb-info-inner">{{ Auth::user()->userProfile->fullName }}</span>
                            <span class="thumb-info-type">{{ implode(", ", Auth::user()->roles->pluck('display_name')->toArray()) }}</span>
                        </div>
                    </div>

                    <div id="remove" class="text-danger" style="@if($user->userProfile->photo=='') display: none; @endif cursor: pointer; text-align: center;"><i class="fa fa-times" aria-hidden="true"></i> {{ trans("profile/front_lang.quitar_foto") }}</div>


                    <hr class="dotted short">

                    <h5 class="mb-2 mt-3">Acerca de</h5>
                    <p class="text-2">
                        {{ trans('general/admin_lang.miembro_desde') }} {{ Auth::user()->created_at_formatted }}
                    </p>


                </div>
            </section>

        </div>
        <div class="col-lg-8 col-xl-9">

            <div class="tabs tabs-secondary">
                <ul class="nav nav-tabs" id="custom-tabs">
                    <li class="nav-item @if ($tab == 'tab_1') active @endif">
                        <a id="tab_1" class="nav-link" data-bs-target="#tab_1-1"
                            data-bs-toggle="tabajax" href="{{ url('profile') }}" data-target="#tab_1-1"
                            aria-controls="tab_1-1" aria-selected="true" >
                            {{ trans('profile/front_lang.perfil_usuario') }}
                        </a>
                    </li>
                    <li class="nav-item @if ($tab == 'tab_2') active @endif">
                        <a id="tab_2" class="nav-link" data-bs-target="#tab_2-2"
                        data-bs-toggle="tabajax" href="{{ url('profile/security') }}" data-target="#tab_2-2"
                        aria-controls="tab_2-2" aria-selected="true" >
                            {{ trans('profile/front_lang.security') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="tab_tabContent">
                    <div id="tab_1-1" class="tab-pane @if ($tab == 'tab_1') active @endif">
                        @yield('tab_content_1')
                    </div>
                    <div id="tab_2-2" class="tab-pane @if ($tab == 'tab_2') active @endif">
                        @yield('tab_content_2')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: page -->

@endsection

@section("foot_page")
    @yield('tab_foot')
@stop
