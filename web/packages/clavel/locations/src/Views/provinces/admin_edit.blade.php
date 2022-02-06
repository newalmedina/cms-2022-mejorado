@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')

@stop

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url("admin/provinces") }}">{{ trans('locations::provinces/admin_lang.province') }}</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')


    @include('admin.includes.errors')
    @include('admin.includes.success')

    {!! Form::model($province, $form_data, array('role' => 'form')) !!}
    {!! Form::hidden("form_return", 0, array('id' => 'form_return')) !!}
    <div class="row">

        <div class="col-12">

            <div class="card card-primary card-outline">
                <div class="card-header with-border"><h3 class="card-title">{{ trans("locations::provinces/admin_lang.info_menu") }}</h3></div>
                <div class="card-body">
                    {{-- belongsToRelationship - country --}}
<div class="form-group row">
    {!! Form::label('country_id', trans('locations::provinces/admin_lang.fields.country'), array('class' => 'col-sm-2 col-form-label')) !!}
    <div class="col-sm-10">
        @php
            $items = [];
        @endphp
        @foreach($countries as $id => $country)
            @php
            $items+= [ $id => $country]
            @endphp
        @endforeach
        {!! Form::select('country_id',
        ['' => trans('locations::provinces/admin_lang.fields.country_helper')] +
        $items
        ,
        null ,
        ['id'=>'country_id', 'class' => 'form-control select2']) !!}
    </div>
</div>
{{-- belongsToRelationship - ccaa --}}
<div class="form-group row">
    {!! Form::label('ccaa_id', trans('locations::provinces/admin_lang.fields.ccaa'), array('class' => 'col-sm-2 col-form-label')) !!}
    <div class="col-sm-10">
        @php
            $items = [];
        @endphp
        @foreach($ccaas as $id => $ccaa)
            @php
            $items+= [ $id => $ccaa]
            @endphp
        @endforeach
        {!! Form::select('ccaa_id',
        ['' => trans('locations::provinces/admin_lang.fields.ccaa_helper')] +
        $items
        ,
        null ,
        ['id'=>'ccaa_id', 'class' => 'form-control select2']) !!}
    </div>
</div>
{{-- Radio yes/no - active --}}
<div class="form-group row">
    {!! Form::label('active', trans('locations::provinces/admin_lang.fields.active'), array('class' => 'col-sm-2 col-form-label')) !!}
    <div class="col-md-10">
        <div class="form-check form-check-inline">
            <label>
                {!! Form::radio('active', 0, true, array('id'=>'active_0', 'class' => 'form-check-input')) !!}
                {!! Form::label('active_0', trans('general/admin_lang.no'), array('class' => 'form-check-label')) !!}
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label>
                {!! Form::radio('active', 1, false, array('id'=>'active_1', 'class' => 'form-check-input')) !!}
                {!! Form::label('active_1', trans('general/admin_lang.yes'), array('class' => 'form-check-label')) !!}
            </label>
        </div>
    </div>
</div>

                </div>
            </div>

            @if(\View::exists('locations::provinces/admin_edit_lang'))
                @include('locations::provinces/admin_edit_lang')
            @endif

            <div class="card">
                <div class="card-footer">
                    <a href="{{ url('/admin/provinces') }}" class="btn btn-secondary">{{ trans('general/admin_lang.cancelar') }}</a>
                    <button type="submit" class="btn btn-primary float-right">{{ trans('general/admin_lang.save') }}</button>
                    <button id="btnSaveReturn" class="btn btn-success float-right" style="margin-right:20px;">{{ trans('general/admin_lang.save_and_return') }}</button>
                </div>
            </div>

        </div>

    </div>
    {!! Form::close() !!}



@endsection

@section("foot_page")

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>


    <script>
        $(document).ready(function() {

        });

        $('#btnSaveReturn').on( 'click', function (event) {
            event.preventDefault();
            $('#form_return').val(1);
            $('#formData').submit();
        } );

    </script>
    {!! JsValidator::formRequest('Clavel\Locations\Requests\AdminProvincesRequest')->selector('#formData') !!}
@stop
