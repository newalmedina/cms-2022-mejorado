@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')

@stop

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url("admin/countries") }}">{{ trans('locations::countries/admin_lang.country') }}</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')


    @include('admin.includes.errors')
    @include('admin.includes.success')

    {!! Form::model($country, $form_data, array('role' => 'form')) !!}
    {!! Form::hidden("form_return", 0, array('id' => 'form_return')) !!}
    <div class="row">

        <div class="col-12">

            <div class="card card-primary card-outline">
                <div class="card-header with-border"><h3 class="card-title">{{ trans("locations::countries/admin_lang.info_menu") }}</h3></div>
                <div class="card-body">
                    {{-- Text - alpha2_code --}}
                    <div class="form-group row">
                        {!! Form::label('alpha2_code', trans('locations::countries/admin_lang.fields.alpha2_code'), array('class' => 'col-sm-2 col-form-label')) !!}
                        <div class="col-sm-10">
                            {!! Form::text('alpha2_code', null , array('placeholder' => trans('locations::countries/admin_lang.fields.alpha2_code_helper'), 'class' => 'form-control', 'id' => 'alpha2_code')) !!}
                        </div>
                    </div>
                    {{-- Text - alpha3_code --}}
                    <div class="form-group row">
                        {!! Form::label('alpha3_code', trans('locations::countries/admin_lang.fields.alpha3_code'), array('class' => 'col-sm-2 col-form-label')) !!}
                        <div class="col-sm-10">
                            {!! Form::text('alpha3_code', null , array('placeholder' => trans('locations::countries/admin_lang.fields.alpha3_code_helper'), 'class' => 'form-control', 'id' => 'alpha3_code')) !!}
                        </div>
                    </div>
                    {{-- Number Integer - numeric_code --}}
                    <div class="form-group row">
                        {!! Form::label('numeric_code', trans('locations::countries/admin_lang.fields.numeric_code'), array('class' => 'col-sm-2 col-form-label')) !!}
                        <div class="col-sm-10">
                            {!! Form::text('numeric_code', null , array('placeholder' => trans('locations::countries/admin_lang.fields.numeric_code_helper'), 'class' => 'form-control', 'id' => 'numeric_code')) !!}
                        </div>
                    </div>
                    {{-- Radio yes/no - active --}}
                    <div class="form-group row">
                        {!! Form::label('active', trans('locations::countries/admin_lang.fields.active'), array('class' => 'col-sm-2 col-form-label')) !!}
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
locations::countries/
            @if(\View::exists('locations::countries/admin_edit_lang'))
                @include('locations::countries/admin_edit_lang')
            @endif

            <div class="card">
                <div class="card-footer">
                    <a href="{{ url('/admin/countries') }}" class="btn btn-secondary">{{ trans('general/admin_lang.cancelar') }}</a>
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
    {!! JsValidator::formRequest('Clavel\Locations\Requests\AdminCountriesRequest')->selector('#formData') !!}
@stop
