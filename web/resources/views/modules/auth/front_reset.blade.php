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
        <a href="/" class="logo float-left">
            <img src="{{ asset("/assets/front/img/logo.svg") }}" height="80" alt="{{ env('APP_NAME') }}" />
        </a>

        <div class="panel card-sign">
            <div class="card-title-sign mt-3 text-end">
                <h2 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-user me-1"></i> {{ trans("general/front_lang.restablecer") }}</h2>
            </div>
            <div class="card-body">
                @include('front.includes.errors')
                @include('front.includes.success')


                <form id="formData" class="form-horizontal" role="form" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->token }}">
                    <div class="form-group mb-3">
                        {!! Form::label('email', trans('auth/lang.email')) !!}
                        <div class="input-group">
                            <input type="text" name="email" class="form-control form-control-lg" id="email" placeholder="{{ trans("general/front_lang.email") }}"  value="{{ $request->email ?? old('email') }}" required autofocus>
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="float-left" for="password">{{ trans("general/front_lang.password") }}</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="{{ trans("general/front_lang.password") }}" required>
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <div id="pswd_info">
                            <h4>{{ trans('auth/lang._KEY_POSIBILITIES_INFO') }}</h4>
                            <ul>
                                <li id="letter" class="invalid">
                                    {{ trans('auth/lang._KEY_POSIBILITIES_001') }}
                                    <strong>{{ trans('auth/lang._KEY_POSIBILITIES_003') }}</strong>
                                </li>
                                <li id="capital" class="invalid">
                                    {{ trans('auth/lang._KEY_POSIBILITIES_001') }}
                                    <strong>{{ trans('auth/lang._KEY_POSIBILITIES_004') }}</strong>
                                </li>
                                <li id="number" class="invalid">
                                    {{ trans('auth/lang._KEY_POSIBILITIES_001') }}
                                    <strong>{{ trans('auth/lang._KEY_POSIBILITIES_005') }}</strong>
                                </li>
                                <li id="length" class="invalid">
                                    {{ trans('auth/lang._KEY_POSIBILITIES_002') }}
                                    <strong>{{ trans('auth/lang._KEY_POSIBILITIES_006') }}</strong>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="float-left" for="password_confirmation">{{ trans("general/front_lang.rep_password") }}</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" class="form-control form-control-lg" id="password-confirm" placeholder="{{ trans("general/front_lang.rep_password") }}" required>
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 text-end">
                            <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-sync-alt" aria-hidden="true"></i> {{ __('general/front_lang.restablecer') }}</button>
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


<script>
    var pwd1 = true;

    $(document).ready(function() {

        var pwd = $("#formData").find('input[name="password"]');

        if (pwd.val() != '') pwd1 = checkform(pwd.val());

        pwd.keyup(function () {

            pwd1 = checkform($(this).val());

        }).blur(function () {

            $('#pswd_info').fadeOut(500);

        });

        $("#formData").submit(function (event) {
            if(! $(this).valid()) return false;

            var pwd = $("#formData").find('input[name="password"]');

            if (!pwd1 && (pwd.val() != '')) {
                return checkform(pwd);
            }

            return true;
        });




    });

    function checkform(pswd) {
        var pswdlength = false;
        var pswdletter = false;
        var pswduppercase = false;
        var pswdnumber = false;

        if (pswd.length >= 7) {
            $('#length').removeClass('invalid').addClass('valid');
            pswdlength = true;
        } else {
            $('#length').removeClass('valid').addClass('invalid');
        }

        if (pswd.match(/[A-z]/)) {
            $('#letter').removeClass('invalid').addClass('valid');
            pswdletter = true;
        } else {
            $('#letter').removeClass('valid').addClass('invalid');
        }

        if (pswd.match(/[A-Z]/)) {
            $('#capital').removeClass('invalid').addClass('valid');
            pswduppercase = true;
        } else {
            $('#capital').removeClass('valid').addClass('invalid');
        }

        if (pswd.match(/\d/)) {
            $('#number').removeClass('invalid').addClass('valid');
            pswdnumber = true;
        } else {
            $('#number').removeClass('valid').addClass('invalid');
        }

        if (pswdlength && pswdletter && pswduppercase && pswdnumber) {
            $('#pswd_info').fadeOut(500);
            return true;
        } else {
            $('#pswd_info').fadeIn(500);
            return false;
        }
    }


</script>

{!! JsValidator::formRequest('App\Http\Requests\FrontResetPasswordRequest')->selector('#formData') !!}

@stop
