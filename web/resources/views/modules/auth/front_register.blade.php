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
                    <h2 class="title text-uppercase font-weight-bold m-0">
                        <i class="bx bx-user-circle me-1 text-6 position-relative top-5"></i> Registro de usuario
                    </h2>
                </div>
                <div class="card-body">
                    @include('front.includes.errors')
                    @include('front.includes.success')

                    {!! Form::open(['url' => ['register'], 'role' => 'form', 'id' => 'formData', 'method' => 'POST', 'autocomplete' => 'off']) !!}

                        <div class="form-group mb-3">
                            {!! Form::label('user_profile[first_name]', trans('profile/front_lang._NOMBRE_USUARIO'), ['class' => '']) !!} <span class="text-danger">*</span>
                            {!! Form::text('user_profile[first_name]', null, ['placeholder' => trans('users/lang._INSERTAR_NOMBRE_USUARIO'), 'class' => 'form-control', 'id' => 'first_name']) !!}
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('user_profile[last_name]', trans('profile/front_lang._APELLIDOS_USUARIO'), ['class' => '']) !!} <span class="text-danger">*</span>
                            {!! Form::text('user_profile[last_name]', null, ['placeholder' => trans('users/lang._INSERTAR_APELLIDOS_USUARIO'), 'class' => 'form-control', 'id' => 'last_name']) !!}
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('email', trans('profile/front_lang._EMAIL_USUARIO'), ['class' => '']) !!} <span class="text-danger">*</span>
                            {!! Form::text('email', null, ['placeholder' => trans('profile/front_lang._INSERTAR_EMAIL_USUARIO'), 'class' => 'form-control']) !!}
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('nif', trans('profile/front_lang.nif'), ['class' => '']) !!} <span class="text-danger">*</span>
                            {!! Form::text('nif', null, ['placeholder' => trans('profile/front_lang.nif'), 'class' => 'form-control', 'id' => 'nif']) !!}
                        </div>

                        <div class="form-group mb-3">
                            {!! Form::label('phone', trans('profile/front_lang.telefono'), ['class' => '']) !!}
                            {!! Form::text('phone', null, ['placeholder' => trans('profile/front_lang.telefono'), 'class' => 'form-control', 'id' => 'phone']) !!}
                        </div>


                        <div class="form-group mb-0">
                            <div class="row">
                                <div class="col-sm-6 mb-3">

                                    {!! Form::label('password', trans('profile/front_lang._CONTASENYA_USUARIO'), ['class' => '']) !!} <span class="text-danger">*</span>
                                    {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'autocomplete' => 'off']) !!}

                                    <div id="pswd_info">
                                        <h4>{{ trans('auth/front_register._KEY_POSIBILITIES_INFO') }}</h4>
                                        <ul>
                                            <li id="letter" class="invalid">{{ trans('auth/front_register._KEY_POSIBILITIES_001') }}
                                                <strong>{{ trans('auth/front_register._KEY_POSIBILITIES_003') }}</strong></li>
                                            <li id="capital" class="invalid">{{ trans('auth/front_register._KEY_POSIBILITIES_001') }}
                                                <strong>{{ trans('auth/front_register._KEY_POSIBILITIES_004') }}</strong></li>
                                            <li id="number" class="invalid">{{ trans('auth/front_register._KEY_POSIBILITIES_001') }}
                                                <strong>{{ trans('auth/front_register._KEY_POSIBILITIES_005') }}</strong></li>
                                            <li id="length" class="invalid">{{ trans('auth/front_register._KEY_POSIBILITIES_002') }}
                                                <strong>{{ trans('auth/front_register._KEY_POSIBILITIES_006') }}</strong></li>
                                        </ul>
                                    </div>

                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-group mb-3">
                                        {!! Form::label('password_confirmation', trans('profile/front_lang._REPETIR_CONTASENYA_USUARIO'), array('class' => '')) !!}
                                        {!! Form::password('password_confirmation', array('placeholder' => trans('auth/front_register.indicar_repassword'), 'class' => 'form-control', 'id' => 'password_confirmation', 'autocomplete' => 'off')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('user_profile[confirmed]',1,false, array('class' =>'', 'id' => "confirmed")) !!}

                                            Acepta las condiciciones de uso
                                            <a href="{{ url('/pages/politica-de-privacidad')  }}" target="_blank">Política de privacidad</a> y
                                            <a href="{{ url('/pages/aviso-legal') }}" target="_blank">Aviso legal</a>.

                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-sm-12">
                                <div id="recaptcha"></div>
                                <div id="message-error-captcha" style="color: #a94442; visibility:hidden;">El campo No soy un robot
                                    es obligatorio.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary mt-2 float-end"><i class="fas fa-paper-plane" aria-hidden="true"></i> {{ __('general/front_lang.enviar') }}</button>
                            </div>
                        </div>


                        <span class="mt-3 mb-3 line-thru text-center text-uppercase">
                            <span>or</span>
                        </span>

                        <div class="mb-1 text-center">
                            <a class="btn btn-facebook mb-3 ms-1 me-1" href="#">Connect with <i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-twitter mb-3 ms-1 me-1" href="#">Connect with <i class="fab fa-twitter"></i></a>
                        </div>


                        <p class="text-center mt-3">{{ __('auth/lang.remember_pass') }} <a href="{{ url('login') }}">{{ __('auth/lang.sign_in_web') }}</a></p>

                    {!! Form::close() !!}
                </div>
            </div>

            <p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2017. All Rights Reserved.</p>
        </div>
    </section>
    <!-- end: page -->

@endsection

@section("foot_page")
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script type="text/javascript"
        src="https://www.google.com/recaptcha/api.js?hl={{ config("app.locale") }}&onload=onloadCallback&render=explicit"
        async defer></script>

    <script>
        var pwd1 = true;

        $(document).ready(function() {

            if ($("#password_confirmation").val() != '') pwd1 = checkform($("#password_confirmation").val());

            $('#password_confirmation').keyup(function () {

                pwd1 = checkform($(this).val());

            }).blur(function () {

                $('#pswd_info').fadeOut(500);

            });

            $("#formData").submit(function (event) {
                if(! $(this).valid()) return false;

                var g_response = grecaptcha.getResponse();
                if(g_response === ''){
                    var messageErrorCaptcha = document.getElementById('message-error-captcha')
                    messageErrorCaptcha.style.visibility='visible';
                    setTimeout(function(){
                        var messageErrorCaptcha = document.getElementById('message-error-captcha')
                        messageErrorCaptcha.style.visibility='hidden';
                    }, 3000);
                    return false;
                }

                if (!pwd1 && ($("#password_confirmation").val() != '')) {
                    return checkform($("#password_confirmation").val());
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

        var onloadCallback = function() {
            grecaptcha.render('recaptcha', {
                'sitekey' : '{!!  Settings::get('google_recaptcha_html_key')  !!}'
            });
        };
    </script>

    {!! JsValidator::formRequest('App\Http\Requests\FrontRegisterUserRequest')->selector('#formData') !!}
@stop
