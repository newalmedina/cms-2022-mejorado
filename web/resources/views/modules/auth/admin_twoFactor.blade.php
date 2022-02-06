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

            <form method="POST" action="{{ route('admin.2fa.verify.store') }}">
                @csrf
                <h2 class="text-center">Two Factor Verification</h2>
                <p class="login-box-msg"">
                    Debe haber recibido un email que contiene el código de doble factor.
                    Si no lo ha recibido, pulse <a href="{{ route('admin.2fa.verify.resend') }}">aquí</a>.
                </p>

                <div class="input-group mb-3">
                    <input name="two_factor_code" type="text" class="form-control{{ $errors->has('two_factor_code') ? ' is-invalid' : '' }}" required autofocus placeholder="Two Factor Code">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @if($errors->has('two_factor_code'))
                        <div class="invalid-feedback">
                            {{ $errors->first('two_factor_code') }}
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-6">
                        <a class="btn btn-danger px-4" href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                            Logout
                        </a>
                    </div>
                    <div class="col-6 text-right">
                        <button type="submit" class="btn btn-primary px-4">
                            Verify
                        </button>
                    </div>
                </div>
            </form>


        </div>

        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<form id="logoutform" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
@endsection

@section('foot_page')

@stop

