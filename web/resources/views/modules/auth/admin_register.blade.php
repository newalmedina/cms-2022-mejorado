@extends('admin.layouts.simple')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('/assets/admin/vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('home') }}">
                <div>{{ config('app.name') }}</div>
            </a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Register a new membership</p>

                @include('admin.includes.errors')
                @include('admin.includes.success')

                <form action="{{ route('admin.register') }}" method="post">
                    @csrf

                    <div class="input-group mb-3 @error('name') has-error @enderror">
                        <input id="username" type="text" class="form-control" placeholder="User name" name="username"
                            value="{{ old('username') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>


                    <div class="input-group mb-3 @error('email') has-error @enderror">
                        <input id="email" type="email" class="form-control" placeholder="Email" name="email"
                            value="{{ old('email') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>


                    <div class="input-group mb-3 @error('password') has-error @enderror">
                        <input id="password" type="password" class="form-control" placeholder="Password" name="password"
                            required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input id="password-confirm" type="password" class="form-control" placeholder="Retype password"
                            name="password_confirmation" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                <label for="agreeTerms">
                                    I agree to the <a href="#">terms</a>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                        <!-- /.col -->
                    </div>


                </form>

                <hr />
                <a href="{{ route('admin.login') }}" class="text-center">I already have a membership</a>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
    <!-- /.register-box -->

@stop



@section('foot_page')


@stop
