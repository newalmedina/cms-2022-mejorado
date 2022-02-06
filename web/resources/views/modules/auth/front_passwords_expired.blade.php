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
            <a href="{{ url('/') }}" class="logo float-left">
                <img src="{{ asset('/assets/front/img/logo.svg') }}" height="80" alt="{{ env('APP_NAME') }}" />
            </a>

            <div class="panel card-sign">
                <div class="card-title-sign mt-3 text-end">
                    <h2 class="title text-uppercase font-weight-bold m-0">
                        <i class="bx bx-user-circle me-1 text-6 position-relative top-5"></i>
                        Contraseña caducada
                    </h2>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        @include('front.includes.success')

                        <div class="row mt-4">
                            <div class="col-sm-12">
                                <a href="{{ url('/') }}" class="btn btn-primary mt-2 float-end">

                                    Volver a inicio
                                </a>
                            </div>
                        </div>
                    @else

                        @include('front.includes.errors')


                        <div class="alert alert-info">
                            <p class="m-0">Tu contraseña ha caducado, por favor, cámbiela.</p>
                        </div>

                        <form id='formData' class="form-horizontal" method="POST" action="{{ route('password.post_expired') }}">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="current_password">Contraseña actual</label>

                                <div class="input-group">
                                    <input id="current_password" type="password" class="form-control" name="current_password" placeholder="Contraseña actual" required autofocus >
                                    <span class="input-group-text">
                                        <i class="bx bx-lock text-4"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">Nueva contraseña</label>

                                <div class="input-group">
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Nueva contraseña" required>
                                    <span class="input-group-text">
                                        <i class="bx bx-lock text-4"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">Confirmar nueva contraseña</label>
                                <div class="input-group">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirmar nueva contraseña" required>
                                    <span class="input-group-text">
                                        <i class="bx bx-lock text-4"></i>
                                    </span>
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary mt-2 float-end">
                                        <i class="fas fa-paper-plane" aria-hidden="true"></i>
                                        Cambiar contraseña
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif

                    <div class="row mt-4">
                        <div class="col-sm-12">
                            <a href="{{ route('password.expired-forgot') }}" class="float-start">{{ trans("users/lang.olvidaste_password") }}</a>
                        </div>
                    </div>

                </div>
            </div>

            <p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2017. All Rights Reserved.</p>
        </div>
    </section>
    <!-- end: page -->
@endsection


@section('foot_page')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

{!! JsValidator::formRequest('App\Http\Requests\Auth\FrontPasswordExpiredRequest')->selector('#formData') !!}


@stop
