@extends('Settings::admin_index')

@section('tab_head')

@stop

@section('tab_breadcrumb')
    <li class="breadcrumb-item active">{{ $tab_title ?? '' }}</li>
@stop




@section('tab_content_1')
{{ Form::open(['url' => 'admin/settings', 'method' => 'POST', 'id' => 'formFolder']) }}

@foreach ($settings as $setting)
    <div class="form-group row">
        <div class="col-12">
        {!! Form::label($setting->key, $setting->display_name, ['class' => 'control-label']) !!}
        {!! Form::text($setting->key, $setting->value, ['placeholder' => $setting->display_name, 'class' => 'form-control', 'id' => $setting->key]) !!}
        </div>
    </div>
@endforeach

<div class="card">

    <div class="card-footer">

        <a href="{{ url('/admin/settings') }}"
            class="btn btn-default">{{ trans('Settings::admin_lang.cancelar') }}</a>
        <button name="save" type="submit"
            class="btn btn-info float-right">{{ trans('Settings::admin_lang.save') }}</button>

    </div>

</div>


{!! Form::close() !!}
@endsection


@section('tab_foot')



@stop
