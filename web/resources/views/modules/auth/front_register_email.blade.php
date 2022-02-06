@extends('front.email.default')

@section('content')


    <p>
        Estimad@ usuari@ {{ $user->userProfile->first_name }},<br>
        Bienvenido a {{ env('APP_NAME') }}, recibida solicitud de registro.
    </p>
    <p>Ya puede acceder al mismo desde la URL: <strong><a href="{{ url ('/') }}">{{ url ('/') }}</a></strong>.</p>

    <p>{{ trans("auth/front_register.email_002") }}</p>
    <p><strong><a href="{{ url("register/confirm/".md5($user->id)) }}">{{ url("register/confirm/".md5($user->id)) }}</a></strong><br><br></p>

    <p>Su usuario es: {{ $user->username }}, y recuerde que su contraseña es la que ha introducido usted al registrarse.</p>
    <p>Si tiene cualquier duda que necesite aclaración, no dude en ponerse en contacto con nosotros.</p>
    <p>Muchas gracias por su colaboración,</p>

@endsection
