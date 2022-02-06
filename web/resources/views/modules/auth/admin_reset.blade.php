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
                <p class="login-box-msg">{{ trans('auth/lang.change_password_info') }}</p>

                @include('admin.includes.errors')
                @include('admin.includes.success')

                <form method="POST" action="{{ route('admin.password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="input-group mb-3 {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="email" type="email" class="form-control" placeholder="{{ trans('auth/lang.email') }}"
                            name="email" value="{{ $request->email ?? old('email') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3 {{ $errors->has('password') ? ' has-error' : '' }}">
                        <input id="password" type="password" class="form-control"
                            placeholder="{{ trans('auth/lang.password') }}" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>

                    </div>


                    <div class="input-group mb-3">
                        <input id="password-confirm" type="password" class="form-control"
                            placeholder="{{ trans('auth/lang.repetir_password') }}" name="password_confirmation"
                            required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit"
                                class="btn btn-primary btn-block">{{ trans('auth/lang.cambiar_contrasena') }}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="{{ route('admin.login') }}">Login</a>
                </p>

            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->


@stop



@section('foot_page')
@stop
