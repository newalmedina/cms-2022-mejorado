@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')
    @parent
    <link href="{{ asset('/assets/admin/vendor/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css') }}"
        rel="stylesheet" type="text/css" />
@stop


@section('breadcrumb')
    <li class="breadcrumb-item"><a
            href="{{ url('admin/crud-generator') }}">{{ trans('crud-generator::modules/admin_lang.list') }}</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')

    @include('admin.includes.errors')
    @include('admin.includes.success')

    {!! Form::model($module, $form_data, ['role' => 'form']) !!}
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header  with-border">
                    <h3 class="card-title">{{ trans('general/admin_lang.info_menu') }}</h3>
                </div>
                <div class="card-body">

                    <div class="form-group row">
                        {!! Form::label('title', trans('crud-generator::modules/admin_lang.name'), ['class' => 'col-sm-2 control-label required required-input']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('title', null, ['placeholder' => trans('crud-generator::modules/admin_lang.name'), 'class' => 'form-control input-xlarge', 'id' => 'title']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('model', trans('crud-generator::modules/admin_lang.model'), ['class' => 'col-sm-2 control-label required required-input']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('model', null, ['placeholder' => trans('crud-generator::modules/admin_lang.model'), 'class' => 'form-control input-xlarge', 'style' => 'width:50%; display:inline;', 'id' => 'model', !empty($module->id) ? 'readonly' : '']) !!}
                            {!! __('crud-generator::modules/admin_lang.model_info') !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('model_plural', trans('crud-generator::modules/admin_lang.model_plural'), ['class' => 'col-sm-2 control-label required required-input']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('model_plural', null, ['placeholder' => trans('crud-generator::modules/admin_lang.model_plural'), 'class' => 'form-control input-xlarge', 'style' => 'width:50%; display:inline;', 'id' => 'model_plural', !empty($module->id) ? 'readonly' : '']) !!}
                            {!! __('crud-generator::modules/admin_lang.model_plural_info') !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('table_name', trans('crud-generator::modules/admin_lang.table_name'), ['class' => 'col-sm-2 control-label required required-input']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('table_name', null, ['placeholder' => trans('crud-generator::modules/admin_lang.table_name'), 'class' => 'form-control input-xlarge', 'style' => 'width:50%; display:inline;', 'id' => 'table_name']) !!}
                            {!! __('crud-generator::modules/admin_lang.table_name_info') !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('type', trans('crud-generator::modules/admin_lang.type'), ['class' => 'col-sm-2 control-label required required-input']) !!}
                        <div class="col-sm-10">
                            {!! Form::select('type', ['' => trans('crud-generator::modules/admin_lang.select_option')] + $type_list, !empty($module->type_id) ? $module->type_id : null, ['id' => 'type', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('theme', trans('crud-generator::modules/admin_lang.theme'), ['class' => 'col-sm-2 control-label required required-input']) !!}
                        <div class="col-sm-10">
                            {!! Form::select('theme', ['' => trans('crud-generator::modules/admin_lang.select_option')] + $theme_list, !empty($module->theme_id) ? $module->theme_id : null, ['id' => 'theme', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('icon', trans('crud-generator::modules/admin_lang.icon'), ['class' => 'col-sm-2 control-label required required-input']) !!}
                        <div class="col-sm-10">
                            {!! Form::hidden('icon', null, ['id' => 'icon']) !!}

                            <div class="btn-group">
                                <button data-selected="graduation-cap" type="button"
                                    class="icp demo btn btn-default dropdown-toggle iconpicker-component"
                                    data-toggle="dropdown">
                                    <i class="{{ $module->icon }}" aria-hidden="true"></i>
                                    <span class="caret"></span>
                                </button>
                                <div class="dropdown-menu"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('active', trans('crud-generator::modules/admin_lang.active'), ['class' => 'col-sm-2 control-label', 'readonly' => true]) !!}
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                {!! Form::radio('active', '0', true, ['id' => 'active_0', 'class' => 'form-check-input']) !!}
                                {!! Form::label('active_0', trans('general/admin_lang.no'), ['class' => 'form-check-label']) !!}
                            </div>
                            <div class="form-check form-check-inline">
                                {!! Form::radio('active', '1', false, ['id' => 'active_1', 'class' => 'form-check-input']) !!}
                                {!! Form::label('active_1', trans('general/admin_lang.yes'), ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('has_soft_deletes', trans('crud-generator::modules/admin_lang.has_soft_deletes'), ['class' => 'col-sm-2 control-label', 'readonly' => true]) !!}
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_soft_deletes', '0', true, ['id' => 'has_soft_deletes_0', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_soft_deletes_0', trans('general/admin_lang.no'), ['class' => 'form-check-label']) !!}
                            </div>
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_soft_deletes', '1', false, ['id' => 'has_soft_deletes_1', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_soft_deletes_1', trans('general/admin_lang.yes'), ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('has_api_crud', trans('crud-generator::modules/admin_lang.has_api_crud'), ['class' => 'col-sm-2 control-label', 'readonly' => true]) !!}
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_api_crud', '0', true, ['id' => 'has_api_crud_0', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_api_crud_0', trans('general/admin_lang.no'), ['class' => 'form-check-label']) !!}
                            </div>
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_api_crud', '1', false, ['id' => 'has_api_crud_1', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_api_crud_1', trans('general/admin_lang.yes'), ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('has_api_crud_secure', trans('crud-generator::modules/admin_lang.has_api_crud_secure'), ['class' => 'col-sm-2 control-label', 'readonly' => true]) !!}
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_api_crud_secure', '0', true, ['id' => 'has_api_crud_secure_0', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_api_crud_secure_0', trans('general/admin_lang.no'), ['class' => 'form-check-label']) !!}
                            </div>
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_api_crud_secure', '1', false, ['id' => 'has_api_crud_secure_1', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_api_crud_secure_1', trans('general/admin_lang.yes'), ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('has_create_form', trans('crud-generator::modules/admin_lang.has_create_form'), ['class' => 'col-sm-2 control-label', 'readonly' => true]) !!}
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_create_form', '0', true, ['id' => 'has_create_form_0', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_create_form_0', trans('general/admin_lang.no'), ['class' => 'form-check-label']) !!}
                            </div>
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_create_form', '1', false, ['id' => 'has_create_form_1', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_create_form_1', trans('general/admin_lang.yes'), ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('has_edit_form', trans('crud-generator::modules/admin_lang.has_edit_form'), ['class' => 'col-sm-2 control-label', 'readonly' => true]) !!}
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_edit_form', '0', true, ['id' => 'has_edit_form_0', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_edit_form_0', trans('general/admin_lang.no'), ['class' => 'form-check-label']) !!}
                            </div>
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_edit_form', '1', false, ['id' => 'has_edit_form_1', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_edit_form_1', trans('general/admin_lang.yes'), ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('has_show_form', trans('crud-generator::modules/admin_lang.has_show_form'), ['class' => 'col-sm-2 control-label', 'readonly' => true]) !!}
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_show_form', '0', true, ['id' => 'has_show_form_0', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_show_form_0', trans('general/admin_lang.no'), ['class' => 'form-check-label']) !!}
                            </div>
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_show_form', '1', false, ['id' => 'has_show_form_1', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_show_form_1', trans('general/admin_lang.yes'), ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('has_delete_form', trans('crud-generator::modules/admin_lang.has_delete_form'), ['class' => 'col-sm-2 control-label', 'readonly' => true]) !!}
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_delete_form', '0', true, ['id' => 'has_delete_form_0', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_delete_form_0', trans('general/admin_lang.no'), ['class' => 'form-check-label']) !!}
                            </div>
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_delete_form', '1', false, ['id' => 'has_delete_form_1', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_delete_form_1', trans('general/admin_lang.yes'), ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('has_exports', trans('crud-generator::modules/admin_lang.has_exports'), ['class' => 'col-sm-2 control-label', 'readonly' => true]) !!}
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_exports', '0', true, ['id' => 'has_exports_0', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_exports_0', trans('general/admin_lang.no'), ['class' => 'form-check-label']) !!}
                            </div>
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_exports', '1', false, ['id' => 'has_exports_1', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_exports_1', trans('general/admin_lang.yes'), ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('has_fake_data', trans('crud-generator::modules/admin_lang.has_fake_data'), ['class' => 'col-sm-2 control-label', 'readonly' => true]) !!}
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_fake_data', '0', true, ['id' => 'has_fake_data_0', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_fake_data_0', trans('general/admin_lang.no'), ['class' => 'form-check-label']) !!}
                            </div>
                            <div class="form-check form-check-inline">
                                {!! Form::radio('has_fake_data', '1', false, ['id' => 'has_fake_data_1', 'class' => 'form-check-input']) !!}
                                {!! Form::label('has_fake_data_1', trans('general/admin_lang.yes'), ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('entries_page', trans('crud-generator::modules/admin_lang.entries_page'), ['class' => 'col-sm-2 control-label required required-input']) !!}
                        <div class="col-sm-10">
                            {!! Form::select('entries_page', ['' => trans('crud-generator::modules/admin_lang.select_option')] + $entries_page_list, !empty($module->entries_page) ? $module->entries_page : null, ['id' => 'entries_page', 'class' => 'form-control select2']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('order_by_field', trans('crud-generator::modules/admin_lang.order_by_field'), ['class' => 'col-sm-2 control-label required required-input']) !!}
                        <div class="col-sm-10">
                            {!! Form::select('order_by_field', ['' => trans('crud-generator::modules/admin_lang.select_option')] + $order_by_field_list, !empty($module->order_by_field) ? $module->order_by_field : null, ['id' => 'order_by_field', 'class' => 'form-control select2']) !!}
                        </div>
                    </div>


                    <div class="form-group row">
                        {!! Form::label('order_direction', trans('crud-generator::modules/admin_lang.order_direction'), ['class' => 'col-sm-2 control-label required required-input']) !!}
                        <div class="col-sm-10">
                            {!! Form::select('order_direction', ['' => trans('crud-generator::modules/admin_lang.select_option')] + $order_direction_list, !empty($module->order_direction) ? $module->order_direction : null, ['id' => 'order_direction', 'class' => 'form-control select2']) !!}
                        </div>
                    </div>

                </div>
            </div>

            <div class="card card-solid">
                <div class="card-footer">
                    <a href="{{ url('/admin/crud-generator') }}"
                        class="btn btn-default">{{ trans('general/admin_lang.cancelar') }}</a>
                    <button type="submit" class="btn btn-info float-right text-light">{{ trans('general/admin_lang.save') }}</button>

                </div>
            </div>
        </div> <!-- class="col-12" -->



    </div>
    {!! Form::close() !!}
@endsection

@section('foot_page')
    <script src="{{ asset('/assets/admin/vendor/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

    <script>
        $(document).ready(function() {

        });

        @if (empty($module->id))
            $( "#title" ).change(function() {
            var title = $( "#title" ).val();

            $( "#model" ).val( Modelo(title));
            $( "#model_plural" ).val( ModeloPlural(title));
            $( "#table_name" ).val( ModeloPluralLowercase(title));
            });
        @endif

        $('.demo').iconpicker();
        $('.demo').on('iconpickerSelected', function(event) {
            $('#icon').val(event.iconpickerValue);
        });

        function Capitalizar(str) {
            var splitStr = str.toLowerCase().split(' ');
            for (var i = 0; i < splitStr.length; i++) {
                // You do not need to check if i is larger than splitStr length, as your for does that for you
                // Assign it back to the array
                splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
            }
            // Directly return the joined string
            return splitStr.join(' ');
        }


        function Modelo(str) {
            str = Capitalizar(str);
            str = str.replace(/\s/g, "");
            return str;
        }


        function ModeloLowercase(str) {
            str = Modelo(str).toLowerCase();
            return str;
        }

        function ModeloPlural(str) {
            return Modelo(str) + (str.endsWith('s') ? '' : 's');
        }

        function ModeloPluralLowercase(str) {
            return ModeloPlural(str).toLowerCase();
        }
    </script>

    {!! JsValidator::formRequest('Clavel\CrudGenerator\Requests\ModuleRequest')->selector('#formData') !!}
@stop
