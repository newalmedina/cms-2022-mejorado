@extends('front.layouts.home')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')
    <!-- Landing Page CSS -->
    <link href="{{ asset("/assets/front/css/home.css") }}" rel="stylesheet" type="text/css" />


@stop

@section('content')

<section class="section section-concept section-no-border section-dark section-angled section-angled-reverse border-top-0 pt-5 m-0" id="section-concept" style="background-size: cover; background-position: center; animation-duration: 750ms; animation-delay: 300ms; animation-fill-mode: forwards;" data-plugin-lazyload data-plugin-options="{'threshold': 500}" data-original="{{ asset("/assets/front/img/landing/header_bg.jpg") }}">
    <div class="section-angled-layer-bottom bg-light"></div>
    <div class="container pt-5 mt-lg-5">
        <div class="row align-items-center pt-3">
            <div class="col-lg-5 mb-5">
                <h5 class="text-primary font-weight-bold mb-1 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-duration="750">PORTO ADMIN HTML5 TEMPLATE</h5>
                <h1 class="font-weight-bold text-color-light text-13 line-height-2 mt-0 mb-3 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400" data-appear-animation-duration="750">The Best Admin Template</h1>
                <p class="custom-font-size-1 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="900" data-appear-animation-duration="750">Porto Admin is simply the best choice for your new project. The template is several years among the most popular in the world.
                    @if (!Auth::guest())
                        <a href="{{ url('dashboard') }}" class="text-color-light font-weight-semibold text-1 d-block">
                            VIEW MORE <i class="fa fa-long-arrow-alt-right ml-1"></i>
                        </a>

                    @endif

                </p>

            </div>
        </div>
    </div>
</section>

<section id="intro" class="section section-no-border bg-light border-top-0 pt-0 pb-0 m-0">

    <div class="container pb-5" >
        <div class="row counters justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5 text-center">
                <h2 class="font-weight-bold text-color-dark text-9 mt-0mb-0 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-duration="750" data-plugin-options="{'accY': -200}">The Perfect Template for a Professional Admin Project</h2>
           </div>
            <div class="col-lg-9 text-center">
                <p class="text-1rem text-color-default negative-ls-05 pt-2 pb-4 mb-4  appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="500" data-appear-animation-duration="750">Porto Admin is simply a better choice for your new project. The template is several years among the most popular in the world, being constantly improved and following the trends of design and best practices of code. Your search for the best solution is over, get your own copy and join our happy customers.</p>
            </div>
        </div>

        <div class="intro row align-items-center justify-content-center pb-5 pb-md-0">
            <div class="col-md-12 col-lg-6 col-xl-5 text-center">
                <img  class="img-fluid" width="400" src="{{ asset("/assets/front/img/lazy.png") }}" data-plugin-lazyload data-plugin-options="{'threshold': 500, 'effect':'fadeIn'}" data-original="{{ asset("/assets/front/img/landing/intro1.jpg") }}" class="img-fluid box-shadow-3 position-relative z-index-1 rounded d-none d-md-block" alt="Screenshot 1" style="left: -110px;">
            </div>
        </div>
    </div>
</section>




@endsection

@section("foot_page")




@stop
