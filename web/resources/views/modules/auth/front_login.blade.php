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
					<img src="{{ asset("/assets/front/img/logo.svg") }}" height="80" alt="{{ env('APP_NAME') }}" />
				</a>

				<div class="panel card-sign">
					<div class="card-title-sign mt-3 text-end">
						<h2 class="title text-uppercase font-weight-bold m-0"><i class="bx bx-user-circle me-1 text-6 position-relative top-5"></i> {{ trans("auth/lang.sign_in_web") }}</h2>
					</div>
					<div class="card-body">
                        @include('front.includes.errors')
                        @include('front.includes.success')

						<form id="frmLogin" class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
							@csrf
                            <div class="form-group mb-3">
								<label>{{ trans("auth/front_lang.user_or_email") }}</label>
								<div class="input-group">
									<input name="login" type="text" class="form-control form-control-lg"  id="login" placeholder="{{ trans("auth/front_lang.user_or_email") }}" value="{{ old('login') }}" required autofocus />
									<span class="input-group-text">
										<i class="bx bx-user text-4"></i>
									</span>
								</div>
							</div>
                            @if ($errors->has('username'))
                                <div class="form-control-feedback">
                                    <span class="text-danger align-middle">
                                        <i class="fa fa-close" aria-hidden="true"></i> {{ $errors->first('username') }}
                                    </span>
                                </div>
                            @endif

							<div class="form-group mb-3">
								<div class="clearfix">
									<label class="float-left">{{ trans("users/lang.password") }}</label>
									<a href="{{ route('password.request') }}" class="float-end">{{ trans("users/lang.olvidaste_password") }}</a>
								</div>
								<div class="input-group">
									<input name="password" type="password" class="form-control form-control-lg"  id="password" placeholder="{{ trans("users/lang.password") }}" required />
									<span class="input-group-text">
										<i class="bx bx-lock text-4"></i>
									</span>
								</div>
							</div>
                            @if ($errors->has('password'))
                                <div class="form-control-feedback">
                                    <span class="text-danger align-middle">
                                        <i class="fa fa-close" aria-hidden="true"></i> {{ $errors->first('password') }}
                                    </span>
                                </div>
                            @endif

							<div class="row">
								<div class="col-sm-6">
									<div class="checkbox-custom checkbox-default">
										<input id="remember" name="remember" type="checkbox"  {{ old('remember') ? 'checked' : '' }}/>
										<label for="remember">Recuérdame</label>
									</div>
								</div>
								<div class="col-sm-6 text-end">
									<button type="submit" class="btn btn-primary mt-2">
                                        <i class="fas fa-sign-in-alt" aria-hidden="true"></i> {{ __('auth/lang.sign_in_web') }}
                                    </button>
								</div>
							</div>



                            @if (Route::has('register'))
                                <hr>
							    <p class="text-center">
                                    {{ __('auth/lang.no_tiene_cuenta') }} <a href="{{ route('register') }}">{{ __('auth/lang.sign_up') }}</a>
                                </p>
                            @endif
						</form>
					</div>
				</div>

				<p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2021. All Rights Reserved.</p>
			</div>
		</section>
		<!-- end: page -->

@endsection

@section('foot_page')


@stop
