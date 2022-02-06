@extends('Settings::admin_index')

@section('tab_head')

@stop

@section('tab_breadcrumb')
    <li class="breadcrumb-item active">{{ $tab_title ?? '' }}</li>
@stop


@section('tab_content_2')
{{ Form::open(['url' => 'admin/settings/mail', 'method' => 'POST', 'id' => 'formFolder']) }}

<div class="alert alert-info alert-dismissible">
    <h5 class="text-light"><i class="icon fas fa-info"></i> Información</h5>
    <p class="text-light">
    A continuación se muestran los valores configurados en el sistema para poder realizar una prueba de correo.
    Si la prueba falla se tienen que configurar correctamente.
    </p>
</div>

<div class="form-group row">
    <div class="col-12">
        {!! Form::label('receiver_email', trans('Settings::admin_lang.receiver_email'), ['class' => 'control-label']) !!}
        {!! Form::text('receiver_email', '', ['placeholder' => trans('Settings::admin_lang.receiver_email'), 'class' => 'form-control', 'id' => 'receiver_email']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12">
        {!! Form::label('host', trans('Settings::admin_lang.mail_host'), ['class' => 'control-label']) !!}
        {!! Form::text('host', env('MAIL_HOST', ''), ['placeholder' => trans('Settings::admin_lang.mail_host'), 'class' => 'form-control', 'id' => 'host', 'disabled' => 'disabled']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12">
        {!! Form::label('port', trans('Settings::admin_lang.mail_port'), ['class' => 'control-label']) !!}
        {!! Form::text('port', env('MAIL_PORT', ''), ['placeholder' => trans('Settings::admin_lang.mail_port'), 'class' => 'form-control', 'id' => 'port', 'disabled' => 'disabled']) !!}
    </div>
</div>


<div class="form-group row">
    <div class="col-12">
        {!! Form::label('username', trans('Settings::admin_lang.mail_username'), ['class' => 'control-label']) !!}
        {!! Form::text('username', env('MAIL_USERNAME', ''), ['placeholder' => trans('Settings::admin_lang.mail_username'), 'class' => 'form-control', 'id' => 'username', 'disabled' => 'disabled']) !!}
    </div>
</div>


<div class="form-group row">
    <div class="col-12">
        {!! Form::label('password', trans('Settings::admin_lang.mail_password'), ['class' => 'control-label']) !!}
        {!! Form::text('password', env('MAIL_PASSWORD', ''), ['placeholder' => trans('Settings::admin_lang.mail_password'), 'class' => 'form-control', 'id' => 'password', 'disabled' => 'disabled']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12">
        {!! Form::label('encryption', trans('Settings::admin_lang.mail_encryption'), ['class' => 'control-label']) !!}
        {!! Form::text('password', env('MAIL_ENCRYPTION', ''), ['placeholder' => trans('Settings::admin_lang.mail_encryption'), 'class' => 'form-control', 'id' => 'encryption', 'disabled' => 'disabled']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12">
        {!! Form::label('from_address', trans('Settings::admin_lang.mail_from_address'), ['class' => 'control-label']) !!}
        {!! Form::text('from_address', env('MAIL_FROM_ADDRESS', ''), ['placeholder' => trans('Settings::admin_lang.mail_from_address'), 'class' => 'form-control', 'id' => 'from_address', 'disabled' => 'disabled']) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-12">
        {!! Form::label('from_name', trans('Settings::admin_lang.mail_from_name'), ['class' => 'control-label']) !!}
        {!! Form::text('from_name', env('MAIL_FROM_NAME', ''), ['placeholder' => trans('Settings::admin_lang.mail_from_name'), 'class' => 'form-control', 'id' => 'from_name', 'disabled' => 'disabled']) !!}
    </div>
</div>



<div class="card">

    <div class="card-footer">

        <a href="{{ url('/admin/settings') }}"
            class="btn btn-default">{{ trans('Settings::admin_lang.cancelar') }}</a>
        <button name="save" type="submit"
            class="btn btn-info float-right">{{ trans('Settings::admin_lang.send') }}</button>

    </div>

</div>


{!! Form::close() !!}
@endsection

@section('tab_foot')


@stop
