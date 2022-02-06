@extends('modules.profile.front_index')

@section('tab_head')

@stop


@section('tab_content_2')

<section class="card mb-4">
    <header class="card-header">

        <h2 class="card-title">Two Factor Authentication</h2>
    </header>
    <div class="card-body">

        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <p>La autenticación de dos factores (2FA) refuerza la seguridad del acceso al requerir dos métodos (también
                referidos como factores) para verificar su identidad. La autenticación de dos factores protege contra
                phishing, ingeniería social y ataques de fuerza bruta de contraseñas y protege sus inicios de sesión de
                atacantes que explotan credenciales débiles o robadas.</p>
        </div>

        @if (empty($user->userTwoFactor) || !$user->userTwoFactor->two_factor_enable)
            <form class="form-horizontal" method="POST" action="{{ route('2fa.enable2fa') }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    Activar 2FA
                </button>
            </form>
        @elseif($user->userTwoFactor->two_factor_enable)
            <div class="alert alert-success">
                2FA está actualmente <strong>habilitado</strong> en su cuenta.
            </div>
            <p>
                Si está buscando deshabilitar la autenticación de dos factores. Confirme su contraseña y haga clic en Desactivar el botón 2FA.
            </p>
            <form class="form-horizontal" method="POST" action="{{ route('2fa.disable2fa') }}">
                @csrf
                <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                    <label for="change-password" class="control-label">Contraseña actual</label>
                    <input id="current-password" type="password" class="form-control col-md-4"
                        name="current-password" required>
                    @if ($errors->has('current-password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('current-password') }}</strong>
                        </span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary mt-4">Desactivar 2FA</button>
            </form>
        @endif
    </div>
</section>

@endsection


@section('tab_foot')



@stop
