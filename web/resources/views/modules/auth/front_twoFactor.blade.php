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
                        <i class="bx bx-user-circle me-1 text-6 position-relative top-5"></i> Two Factor Verification
                    </h2>
                </div>
                <div class="card-body">
                    @include('front.includes.errors')
                    @include('front.includes.success')

                    <form id='formData' class="form-horizontal" role="form" method="POST" action="{{ route('2fa.verify.store') }}">
                        @csrf

                        <div class="form-group mb-0">
                            <div class="input-group">
                                <label>
                                    Debe haber recibido un email que contiene el código de doble factor.
                                    Si no lo ha recibido, pulse <a href="{{ route('2fa.verify.resend') }}">aquí</a>.
                                </label>

                                <div class="input-group mt-3">
                                    <input name="two_factor_code" type="text" class="form-control form-control-lg {{ $errors->has('two_factor_code') ? ' is-invalid' : '' }}" required autofocus placeholder="Two Factor Code">

									<span class="input-group-text">
										<i class="fas fa-lock text-4"></i>
									</span>
								</div>
                            </div>

                        </div>

                        @if($errors->has('two_factor_code'))
                            <div class="form-control-feedback">
                                <span class="text-danger align-middle">
                                    <i class="fa fa-close" aria-hidden="true"></i> {{ $errors->first('two_factor_code') }}
                                </span>
                            </div>
                        @endif

                        <div class="row mt-5">
                            <div class="col-6">
                                <a class="btn btn-danger px-4" href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                                    Logout
                                </a>
                            </div>
                            <div class="col-6 text-right">
                                <button type="submit" class="btn btn-primary px-4 float-end">
                                    Verify
                                </button>
                            </div>
                        </div>




                    </form>
                </div>
            </div>

            <p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2017. All Rights Reserved.</p>
        </div>
    </section>
    <!-- end: page -->




<form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
@endsection


@section("foot_page")

@stop
