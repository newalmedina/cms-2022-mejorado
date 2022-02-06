@extends('admin.layouts.simple')

@section('title')
    @parent {{ $page_title }}
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
                <p class="login-box-msg">{{ trans('auth/lang.introduzca_email') }}</p>

                @include('admin.includes.errors')
                @include('admin.includes.success')


                <form method="POST" action="{{ route('admin.password.email') }}">
                    @csrf

                    <div class="input-group mb-3 @error('email') has-error @enderror">
                        <input id="login" type="text" class="form-control" placeholder="{{ trans('auth/lang.usuario_or_email') }}" name="login"
                            value="{{ old('login') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit"
                                class="btn btn-primary">{{ trans('auth/lang.enviar_contrasena') }}</button>
                            <a class="btn btn-default float-right"
                                href="{{ url('admin/login') }}">{{ trans('auth/lang.volver_login') }}</a>

                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                @if (Route::has('admin.register'))
                    <p class="mt-3 mb-0">
                        <a href="{{ route('admin.register') }}"
                            class="text-center">{{ __('auth/lang.registrarse_en_la_web') }}</a>
                    </p>
                @endif

            </div>

            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->


@stop
