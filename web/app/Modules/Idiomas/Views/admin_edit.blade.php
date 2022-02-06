@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')

@stop

@section('breadcrumb')
    <li class="breadcrumb-item"><a
            href="{{ url('admin/idiomas') }}">{{ trans('Idiomas::idiomas/admin_lang.idioma') }}</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')


    @include('admin.includes.errors')
    @include('admin.includes.success')

    {!! Form::model($idioma, $form_data, ['role' => 'form']) !!}
    {!! Form::hidden('form_return', 0, ['id' => 'form_return']) !!}

    <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">{{ trans('Idiomas::idiomas/admin_lang.info_menu') }}</h3>
        </div>
        <div class="card-body">
            {{-- Text - code --}}
            <div class="form-group row">
                {!! Form::label('code', trans('Idiomas::idiomas/admin_lang.fields.code'), ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('code', null, ['placeholder' => trans('Idiomas::idiomas/admin_lang.fields.code_helper'), 'class' => 'form-control', 'id' => 'code']) !!}
                </div>
            </div>
            {{-- Radio yes/no - active --}}
            <div class="form-group row">
                {!! Form::label('active', trans('Idiomas::idiomas/admin_lang.fields.active'), ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">
                    <div class="form-check form-check-inline">
                        {!! Form::radio('active', 0, true, ['id' => 'active_0', 'class' => 'form-check-input']) !!}
                        <label class="form-check-label">{{ trans('general/admin_lang.no') }}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        {!! Form::radio('active', 1, false, ['id' => 'active_1', 'class' => 'form-check-input']) !!}
                        <label class="form-check-label">{{ trans('general/admin_lang.yes') }} </label>
                    </div>
                </div>
            </div>

            {{-- Radio yes/no - default --}}
            <div class="form-group row">
                {!! Form::label('default', trans('Idiomas::idiomas/admin_lang.fields.default'), ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">
                    <div class="form-check form-check-inline">

                        {!! Form::radio('default', 0, true, ['id' => 'default_0', 'class' => 'form-check-input']) !!}
                        <label class="form-check-label">{{ trans('general/admin_lang.no') }}</label>
                    </div>
                    <div class="form-check form-check-inline">

                        {!! Form::radio('default', 1, false, ['id' => 'default_1', 'class' => 'form-check-input']) !!}
                        <label class="form-check-label">{{ trans('general/admin_lang.yes') }} </label>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @if (\View::exists('Idiomas::admin_edit_lang'))
        @include('Idiomas::admin_edit_lang')
    @endif

    <div class="card">
        <div class="card-footer">
            <a href="{{ url('/admin/idiomas') }}"
                class="btn btn-default">{{ trans('general/admin_lang.cancelar') }}</a>
            <button type="submit" class="btn btn-info float-right">{{ trans('general/admin_lang.save') }}</button>
            <button id="btnSaveReturn" class="btn btn-success float-right"
                style="margin-right:20px;">{{ trans('general/admin_lang.save_and_return') }}</button>
        </div>
    </div>

    {!! Form::close() !!}

@endsection

@section('foot_page')

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

    <script>

        $('#btnSaveReturn').on('click', function(event) {
            event.preventDefault();
            $('#form_return').val(1);
            $('#formData').submit();
        });
    </script>
    {!! JsValidator::formRequest('App\Modules\Idiomas\Requests\AdminIdiomasRequest')->selector('#formData') !!}
@stop
