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

                <div class="mb-4 text-muted">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </div>

                @include('admin.includes.errors')
                @include('admin.includes.success')

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 text-success">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <div class="mt-4 d-flex justify-content-between">
                    <form method="POST" action="{{ route('admin.verification.send') }}">
                        @csrf

                        <div>
                            <button type="submit" class="btn btn-primary">
                                {{ __('Resend Verification Email') }}
                            </button>
                        </div>
                    </form>



                    <a class="btn btn-danger px-4" href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        Logout
                    </a>

                </div>

            </div>

            <!-- /.login-card-body -->
        </div>

        <div class="login-footer">
            <made-with-love version="{{ config('general.app_version') }}"></made-with-love>
        </div>

    </div>
    <!-- /.login-box -->


    <form id="logoutform" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

@stop



@section('foot_page')
@stop
