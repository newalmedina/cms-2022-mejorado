@extends('front.layouts.simple')

@section('title')
@parent {{ $page_title }}
@stop

@section('head_page')
@stop

@section('content')

    {!! Form::hidden('email', $user->email, ['id' => 'email_confirm']) !!}

    <!-- start: page -->
    <section class="body-sign">
        <div class="center-sign">
            <a href="/" class="logo float-left">
                <img src="{{ asset("/assets/front/img/logo.svg") }}" height="80" alt="{{ env('APP_NAME') }}" />
            </a>

            <div class="panel card-sign">
                <div class="card-title-sign mt-3 text-right">
                    <h2 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-user mr-1"></i> Registro de usuario</h2>
                </div>
                <div class="card-body">

                    <h4 class="text-center"><i class="fa fa-check-circle text-success fa-5x"></i></h4>
                    <h4 class="text-success mb-4"></i> {{ __("auth/front_register.alta_correcta") }}</h4>

                    <div class="alert alert-warning">
                        <p class="m-0">{{ __("auth/front_register.alta_correcta_11") }} {{ __("auth/front_register.alta_correcta_12") }}</p>
                    </div>

                    <p>{{ __("auth/front_register.alta_correcta_13") }}</p>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <button id="send_mail_confirm" type="button" class="btn btn-primary"><i class="fas fa-paper-plane" aria-hidden="true"></i> {{ __("auth/front_register.send_mail") }}</button>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-info pull-right"
                                href="{{ url('/') }}">{{ __("auth/front_register.volver_a_la_home")}}</a>
                        </div>
                    </div>

                    <div id="success_confirmacion" class="alert alert-success" role="alert" style="display: none;">
                        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
                        <strong>{{ date('d/m/Y H:i:s') }}</strong>
                        {{ __("auth/front_register.alta_correcta_11") }}
                    </div>
                    <div id="error_confirmacion" class="alert alert-danger" role="alert" style="display: none;">
                        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
                        <strong>{{ date('d/m/Y H:i:s') }}</strong>
                        <div id="error_text"></div>
                    </div>


                </div>
            </div>

            <p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2017. All Rights Reserved.</p>
        </div>
    </section>
    <!-- end: page -->

@endsection

@section("foot_page")
<script>
    $(document).ready(function() {
            $("#send_mail_confirm").click(function() {
                $("#success_confirmacion").css("display","none");
                $("#error_confirmacion").css("display","none");
                if($("#email_confirm").val()!='') {
                    $.ajax({
                        url: "{{url('/register/send_confirm_mail')}}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN':"{{ csrf_token() }}"
                        },
                        data: {
                            email: $("#email_confirm").val()
                        },
                        success: function ( data ) {
                            if(data!='OK') {
                                $("#error_text").html(data);
                                $("#error_confirmacion").css("display","block");
                                setTimeout(function() {
                                    $('#error_confirmacion').hide()
                                }, 10000);
                            } else {
                                $("#success_confirmacion").css("display","block");
                                setTimeout(function() {
                                    $('#success_confirmacion').hide()
                                }, 10000);
                            }
                        }
                    });

                } else {
                    $("#error_text").html("{!! __("auth/front_register.confirmacion_email_fill") !!}");
                    $("#error_confirmacion").css("display","block");
                    setTimeout(function() {
                        $('#error_confirmacion').hide()
                    }, 4000);
                }
            });
        });
</script>
@stop
