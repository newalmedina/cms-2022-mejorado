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

                @include('admin.includes.errors')
                @include('admin.includes.success')
                <div class="mb-4 text-muted">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </div>


                <form method="POST" action="{{ route('admin.password.confirm') }}">
                    @csrf


                    <!-- Password -->
                    <div class="input-group mb-3 {{ $errors->has('password') ? ' has-error' : '' }}">
                        <input id="password" type="password" class="form-control"
                            placeholder="{{ trans('auth/lang.password') }}" name="password"  autocomplete="current-password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>

                    </div>





                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            {{ __('Confirm') }}
                        </button>

                    </div>
                </form>


            </div>

            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->


@endsection

@section('foot_page')

@stop
