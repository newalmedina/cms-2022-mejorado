@extends('admin.layouts.simple')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')

@stop

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('home') }}">
                <div>{{ config('app.name') }}</div>
            </a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Contraseña caducada</p>



                @if (session('status'))
                    @include('admin.includes.success')

                    <div class="row mt-4">
                        <div class="col-sm-12">
                            <a href="{{ url('/admin') }}" class="btn btn-primary mt-2 float-right">

                                Volver a inicio
                            </a>
                        </div>
                    </div>
                @else
                    @include('admin.includes.errors')
                    <div class="alert alert-info">
                        Tu contraseña ha caducado, por favor, cámbiela.
                    </div>
                    <form id='formData' class="form-horizontal" method="POST" action="{{ route('admin.password.post_expired') }}">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-12">
                                <label for="current_password">Contraseña actual</label>

                                <div class="input-group mb-3">

                                    <input id="current_password" type="password" class="form-control" name="current_password" placeholder="Contraseña actual" required autofocus >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <label for="password">Nueva contraseña</label>

                                <div class="input-group mb-3">
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Nueva contraseña" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="password-confirm">Confirmar nueva contraseña</label>

                                <div class="input-group mb-3">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirmar nueva contraseña" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary mt-2 float-right">
                                    Cambiar contraseña
                                </button>
                            </div>
                        </div>
                    </form>

                    @if (Route::has('admin.password.request'))
                    <p class="mb-1">
                        <a href="{{ route('admin.password.expired-forgot') }}">{{ __('auth/lang.recordar_contrasena') }}</a>
                    </p>
                    @endif
                @endif


            </div>

            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->


@stop

@section('foot_page')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

{!! JsValidator::formRequest('App\Http\Requests\Auth\AdminPasswordExpiredRequest')->selector('#formData') !!}

@stop
