@extends('front.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('title-banner')
    <x-title-banner page-title="{{ $page_title }}" banner="banner--cursos2" />
@stop


@section('content')

    @if(config("general.GMAPS_URL")!='')
        <div class="container bs-docs-container">
            <iframe title="mapa de localización" src="{{ config("general.GMAPS_URL") }}" style="border:0;" allowfullscreen="" width="100%" height="350" frameborder="0"></iframe>
        </div>
    @endif

    <div class="container">


        <div class="row py-4">
            <div class="col-md-8">
                <h2 class="font-weight-bold text-8 mt-2 mb-0">{{ $page_title }}</h2>
                <p class="mb-4">{{ trans("Contacto::front_lang.all_fields_obligatory") }}</p>

                {!! Form::model($user, $form_data, array('role' => 'form')) !!}

                    @include('front.includes.errors')
                    @include('front.includes.success')

                    <div class="row">
                        <div class="form-group col-lg-6">
                            {!! Form::label('fullname', trans('Contacto::front_lang.fullname'), array('class' => 'form-label mb-1 text-2', 'readonly' => true)) !!}
                            {!! Form::text('fullname', (isset($user->user_profile->fullname)) ? $user->user_profile->fullname : null, array('placeholder' => trans('Contacto::front_lang._INSERTAR_fullname'), 'class' => 'form-control text-3 h-auto py-2', 'id' => 'fullname')) !!}

                        </div>

                        <div class="form-group col-lg-6">
                            {!! Form::label('email', trans('Contacto::front_lang.email'), array('class' => 'form-label mb-1 text-2', 'readonly' => true)) !!}
                            {!! Form::text('email', null, array('placeholder' => trans('Contacto::front_lang._INSERTAR_email'), 'class' => 'form-control text-3 h-auto py-2', 'id' => 'email')) !!}
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label mb-1 text-2">Asunto</label>
                            <input type="text" value="" data-msg-required="Please enter the subject." maxlength="100" class="form-control text-3 h-auto py-2" name="subject" required>
                        </div>
                    </div>

                    <div class="form-group row" style="display: none;">
                        <label for="faxonly">Fax Only
                            <input type="checkbox" name="faxonly" id="faxonly" />
                        </label>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            {!! Form::label('message', trans('Contacto::front_lang.message'), array('class' => 'form-label mb-1 text-2', 'readonly' => true)) !!}
                            {!! Form::textarea('message', null, array('class' => 'form-control text-3 h-auto py-2', 'style' => 'resize:none; height:150px;', 'id' => 'message')) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6">
                            <div id="recaptcha"></div>
                            <div id="message-error-captcha" style="color: #a94442; visibility:hidden;">El campo No soy un robot es obligatorio.</div>
                        </div>

                        <div class="form-group col-lg-6">
                            <input type="submit" name="submit" id="submit" value="{{ trans("Contacto::front_lang.submit") }}" class="btn btn-primary btn-modern float-end">
                        </div>

                    </div>

                {!! Form::close() !!}
            </div>

            <div class="col-md-4">

                <div class="appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="800">
                    <h4 class="mt-2 mb-1">Our <strong>¿Donde estamos?</strong></h4>
                    <ul class="list list-icons list-icons-style-2 mt-2">
                        <li><i class="fas fa-map-marker-alt top-6"></i> <strong class="text-dark">Dirección:</strong> 1234 Street Name, City Name, United States</li>
                        <li><i class="fas fa-phone top-6"></i> <strong class="text-dark">Teléfono:</strong> (123) 456-789</li>
                        <li><i class="fas fa-envelope top-6"></i> <strong class="text-dark">Email:</strong> <a href="mailto:mail@example.com">mail@example.com</a></li>
                    </ul>
                </div>

                <div class="appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="950">
                    <h4 class="pt-5">Horario</h4>
                    <ul class="list list-icons list-dark mt-2">
                        <li><i class="far fa-clock top-6"></i> Lunes - Viernes - 9:00 a 17:00</li>
                        <li><i class="far fa-clock top-6"></i> Sabado - 9:00 a 14:00</li>
                        <li><i class="far fa-clock top-6"></i> Domingo - Cerrado</li>
                    </ul>
                </div>

                <h4 class="pt-5">{{ trans("Contacto::front_lang.contacto_info_01") }}</h4>
                <p class="lead mb-0 text-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur eget leo at velit imperdiet varius. In eu ipsum vitae velit congue iaculis vitae at risus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>



            </div>
        </div>

    </div>


@endsection

@section('foot_page')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl={{ config("app.locale") }}&onload=onloadCallback&render=explicit" async defer></script>

    <script>
        $(document).ready(function() {
            $("#contact-form").submit(function( event ) {
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
                return true;
            });
        });

        var onloadCallback = function() {
            grecaptcha.render('recaptcha', {
                'sitekey' : '{!!  Settings::get('google_recaptcha_html_key')  !!}'
            });
        };
    </script>

    {!! JsValidator::formRequest('App\Modules\Contacto\Requests\ContactRequest')->selector('#contact-form') !!}
@stop
