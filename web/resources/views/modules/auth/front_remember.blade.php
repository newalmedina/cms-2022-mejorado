@extends('front.layouts.simple')

@section('title')
    @parent {{ $page_title }}
@stop

@section("head_page")
@stop

@section('content')

    <!-- start: page -->
    <section class="body-sign">
        <div class="center-sign">
            <a href="{{ url('/') }}" class="logo float-left">
                <img src="{{ asset("/assets/front/img/logo.svg") }}" height="80" alt="{{ env('APP_NAME') }}" />
            </a>

            <div class="panel card-sign">
                <div class="card-title-sign mt-3 text-end">
                    <h2 class="title text-uppercase font-weight-bold m-0">
                        <i class="bx bx-user-circle me-1 text-6 position-relative top-5"></i> {{ trans("general/front_lang.recuperar_pass") }}
                    </h2>
                </div>
                <div class="card-body">
                    @include('front.includes.errors')
                    @include('front.includes.success')

                    <div class="alert alert-info">
                        <p class="m-0">{{ trans("auth/lang.recover_info_01") }}</p>
                    </div>

                    <form id='formData' class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group mb-0">
                            <div class="input-group">
                                <label>{{ trans("auth/lang.recover_info_02") }}</label>

                                <div class="input-group">
                                    <input type="text" name="login" class="form-control form-control-lg" id="login" placeholder="{{ trans("users/lang.email_usuario") }}" value="{{ old('login') }}" required autofocus>
									<span class="input-group-text">
										<i class="fas fa-envelope text-4"></i>
									</span>
								</div>
                            </div>

                        </div>
                        @if ($errors->has('username'))
                            <div class="form-control-feedback">
                                <span class="text-danger align-middle">
                                    <i class="fa fa-close" aria-hidden="true"></i> {{ $errors->first('username') }}
                                </span>
                            </div>
                        @endif
                        <p>{{ trans("auth/lang.recover_info_03") }}</p>

                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary mt-2 float-end"><i class="fas fa-paper-plane" aria-hidden="true"></i> {{ __('general/front_lang.enviar') }}</button>
                            </div>
                        </div>

                        <p class="text-center mt-3">{{ __('auth/lang.remember_pass') }} <a href="{{ url('login') }}">{{ __('auth/lang.sign_in_web') }}</a></p>

                    </form>
                </div>
            </div>

            <p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2017. All Rights Reserved.</p>
        </div>
    </section>
    <!-- end: page -->


@endsection


@section("foot_page")
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

{!! JsValidator::formRequest('App\Http\Requests\FrontPassLostRequest')->selector('#formData') !!}

@stop
