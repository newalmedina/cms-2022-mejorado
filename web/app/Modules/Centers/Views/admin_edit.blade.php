@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')

@stop

@section('breadcrumb')
    <li class="breadcrumb-item"><a
            href="{{ url('admin/centers') }}">{{ trans('Centers::centers/admin_lang.center') }}</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')


    @include('admin.includes.errors')
    @include('admin.includes.success')

    {!! Form::model($center, $form_data, ['role' => 'form']) !!}
    {!! Form::hidden('form_return', 0, ['id' => 'form_return']) !!}
    <div class="row">

        <div class="col-12">

            <div class="card card-primary card-outline">
                <div class="card-header with-border">
                    <h3 class="card-title">{{ trans('Centers::centers/admin_lang.info_menu') }}</h3>
                </div>
                <div class="card-body">
                    {{-- Text - name --}}
                    <div class="form-group row">
                        {!! Form::label('name', trans('Centers::centers/admin_lang.fields.name'), ['class' => 'col-sm-2 col-form-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('name', null, ['placeholder' => trans('Centers::centers/admin_lang.fields.name_helper'), 'class' => 'form-control', 'id' => 'name']) !!}
                        </div>
                    </div>
                    {{-- Radio yes/no - active --}}
                    <div class="form-group row">
                        {!! Form::label('active', trans('Centers::centers/admin_lang.fields.active'), ['class' => 'col-sm-2 col-form-label']) !!}
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <label>
                                    {!! Form::radio('active', 0, true, ['id' => 'active_0', 'class' => 'form-check-input']) !!}
                                    {!! Form::label('active_0', trans('general/admin_lang.no'), ['class' => 'form-check-label']) !!}
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label>
                                    {!! Form::radio('active', 1, false, ['id' => 'active_1', 'class' => 'form-check-input']) !!}
                                    {!! Form::label('active_1', trans('general/admin_lang.yes'), ['class' => 'form-check-label']) !!}
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- belongsToRelationship - province --}}
                    <div class="form-group row">
                        {!! Form::label('province_id', trans('Centers::centers/admin_lang.fields.province'), ['class' => 'col-sm-2 col-form-label']) !!}
                        <div class="col-sm-10">
                            @php
                                $items = [];
                            @endphp
                            @foreach ($provinces as $id => $province)
                                @php
                                    $items += [$id => $province];
                                @endphp
                            @endforeach
                            {!! Form::select('province_id', ['' => trans('Centers::centers/admin_lang.fields.province_helper')] + $items, null, ['id' => 'province_id', 'class' => 'form-control select2']) !!}
                        </div>
                    </div>
                    {{-- Text - city --}}
                    <div class="form-group row">
                        {!! Form::label('city', trans('Centers::centers/admin_lang.fields.city'), ['class' => 'col-sm-2 col-form-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('city', null, ['placeholder' => trans('Centers::centers/admin_lang.fields.city_helper'), 'class' => 'form-control', 'id' => 'city']) !!}
                        </div>
                    </div>
                    {{-- Text - cp --}}
                    <div class="form-group row">
                        {!! Form::label('cp', trans('Centers::centers/admin_lang.fields.cp'), ['class' => 'col-sm-2 col-form-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('cp', null, ['placeholder' => trans('Centers::centers/admin_lang.fields.cp_helper'), 'class' => 'form-control', 'id' => 'cp']) !!}
                        </div>
                    </div>
                    {{-- Text - address --}}
                    <div class="form-group row">
                        {!! Form::label('address', trans('Centers::centers/admin_lang.fields.address'), ['class' => 'col-sm-2 col-form-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('address', null, ['placeholder' => trans('Centers::centers/admin_lang.fields.address_helper'), 'class' => 'form-control', 'id' => 'address']) !!}
                        </div>
                    </div>



                    {{-- Text - phone --}}
                    <div class="form-group row">
                        {!! Form::label('phone', trans('Centers::centers/admin_lang.fields.phone'), ['class' => 'col-sm-2 col-form-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('phone', null, ['placeholder' => trans('Centers::centers/admin_lang.fields.phone_helper'), 'class' => 'form-control', 'id' => 'phone']) !!}
                        </div>
                    </div>
                    {{-- Text - email --}}
                    <div class="form-group row">
                        {!! Form::label('email', trans('Centers::centers/admin_lang.fields.email'), ['class' => 'col-sm-2 col-form-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('email', null, ['placeholder' => trans('Centers::centers/admin_lang.fields.email_helper'), 'class' => 'form-control', 'id' => 'email']) !!}
                        </div>
                    </div>
                    {{-- Text - contact --}}
                    <div class="form-group row">
                        {!! Form::label('contact', trans('Centers::centers/admin_lang.fields.contact'), ['class' => 'col-sm-2 col-form-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('contact', null, ['placeholder' => trans('Centers::centers/admin_lang.fields.contact_helper'), 'class' => 'form-control', 'id' => 'contact']) !!}
                        </div>
                    </div>


                </div>
            </div>

            @if (\View::exists('Centers::admin_edit_lang'))
                @include('Centers::admin_edit_lang')
            @endif

            <div class="card">
                <div class="card-footer">
                    <a href="{{ url('/admin/centers') }}"
                        class="btn btn-secondary">{{ trans('general/admin_lang.cancelar') }}</a>
                    <button type="submit"
                        class="btn btn-primary float-right">{{ trans('general/admin_lang.save') }}</button>
                    <button id="btnSaveReturn" class="btn btn-success float-right"
                        style="margin-right:20px;">{{ trans('general/admin_lang.save_and_return') }}</button>
                </div>
            </div>

        </div>

    </div>
    {!! Form::close() !!}



@endsection

@section('foot_page')

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>


    <script>
        $(document).ready(function() {

        });

        $('#btnSaveReturn').on('click', function(event) {
            event.preventDefault();
            $('#form_return').val(1);
            $('#formData').submit();
        });
    </script>
    {!! JsValidator::formRequest('App\Modules\Centers\Requests\AdminCentersRequest')->selector('#formData') !!}
@stop
