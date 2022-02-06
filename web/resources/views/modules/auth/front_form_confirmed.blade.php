@extends('front.layouts.simple')

@section('title')
@parent {{ $page_title }}
@stop

@section('head_page')
@stop

@section('content')
<!-- start: page -->
<section class="body-sign">
    <div class="center-sign">
        <a href="/" class="logo float-left">
            <img src="{{ asset("/assets/front/img/logo.svg") }}" height="80" alt="{{ env('APP_NAME') }}" />
        </a>

        <div class="panel card-sign">
            <div class="card-title-sign mt-3 text-right">
                <h2 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-user mr-1"></i> Registro de usuario</h2>
            </div>
            <div class="card-body">
                <h4 class="text-center txt-primary"><i class="fa fa-check-circle text-success fa-5x"></i></h4>
                <h4 class="text-success mb-4"></i> {{ __("auth/front_register.confirmacion_correcta") }}</h4>

                <div class="alert alert-success mb-5">
                    <p class="m-0">{{ __("auth/front_register.confirmacion_correcta_01") }}</p>
                </div>


                <div class="row mb-5">
                    <div class="col-md-12 text-center">
                        <a class="btn btn-info" href="{{ url('/') }}">{{ __("auth/front_register.volver_a_la_home")}}</a>
                    </div>
                </div>

            </div>
        </div>

        <p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2017. All Rights Reserved.</p>
    </div>
</section>
<!-- end: page -->

@endsection

@section("foot_page")

@stop
