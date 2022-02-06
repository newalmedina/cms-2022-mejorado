@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')
    @parent
@stop


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url("admin/crud-generator") }}">{{ trans('crud-generator::modules/admin_lang.list') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url("admin/crud-generator/".$module->id."/fields") }}">{{ trans('crud-generator::fields/admin_lang.list') }}</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')

    @include('admin.includes.errors')
    @include('admin.includes.success')


    <div class="row">

        

            <div class="col-12">

                <div class="card card-primary">
                    <div class="card-header  with-border"><h3 class="card-title">{{ trans("general/admin_lang.info_menu") }}</h3></div>
                    <div class="card-body">
                        {!! Form::model($field, $form_data, array('role' => 'form')) !!}
                        <div class="form-group row">
                            {!! Form::label('column_name', trans('crud-generator::fields/admin_lang.column_name'), array('class' => 'col-sm-2 control-label required required-input')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('column_name', null,
                                    array('placeholder' => trans('crud-generator::fields/admin_lang.column_name'),
                                    'class' => 'form-control input-xlarge',
                                    'id' => 'column_name',
                                     (!empty($field->id))? 'readonly': ''
                                    )) !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('column_title', trans('crud-generator::fields/admin_lang.column_title'), array('class' => 'col-sm-2 control-label required required-input')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('column_title', null,
                                    array('placeholder' => trans('crud-generator::fields/admin_lang.column_title'),
                                    'class' => 'form-control input-xlarge',
                                    'id' => 'column_title')) !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('column_tooltip', trans('crud-generator::fields/admin_lang.column_tooltip'), array('class' => 'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('column_tooltip', null,
                                    array('placeholder' => trans('crud-generator::fields/admin_lang.column_tooltip'),
                                    'class' => 'form-control input-xlarge',
                                    'id' => 'column_tooltip')) !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('field_type_slug', trans('crud-generator::fields/admin_lang.type'), array('class' => 'col-sm-2 control-label required required-input')) !!}
                            <div class="col-sm-10">
                                <select name="field_type_slug" id="field_type_slug" class="form-control select2" @if(!empty($field->id))disabled="true" @endif>
                                    @foreach($fieldTypes as $key=>$value)
                                        <option value="{{ $value->slug }}" @if($value->slug==$field->field_type_slug) selected @endif>{{ $value->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        {{-- <div class="form-group">
                            {!! Form::label('order_list', trans('crud-generator::fields/admin_lang.order_list'), array('class' => 'col-sm-2 control-label required required-input')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('order_list', null,
                                    array('placeholder' => trans('crud-generator::fields/admin_lang.order_list'),
                                    'class' => 'form-control input-xlarge',
                                    'id' => 'order_list')) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('order_create', trans('crud-generator::fields/admin_lang.order_create'), array('class' => 'col-sm-2 control-label required required-input')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('order_create', null,
                                    array('placeholder' => trans('crud-generator::fields/admin_lang.order_create'),
                                    'class' => 'form-control input-xlarge',
                                    'id' => 'order_create')) !!}
                            </div>
                        </div> --}}

                        <div class="form-group row">
                            {!! Form::label('in_list', trans('crud-generator::fields/admin_lang.in_list'), array('class' => 'col-sm-2 control-label')) !!}
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    {!! Form::radio('in_list', '0', true, array('id'=>'in_list_0', 'class' => 'form-check-input')) !!}
                                    {!! Form::label('in_list_0', trans('general/admin_lang.no'), array('class' =>
                                    'form-check-label')) !!}
                                </div>
                                <div class="form-check form-check-inline">
                                    {!! Form::radio('in_list', '1', false, array('id'=>'in_list_1', 'class' =>'form-check-input')) !!}
                                    {!! Form::label('in_list_1', trans('general/admin_lang.yes'), array('class' =>'form-check-label')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('in_create', trans('crud-generator::fields/admin_lang.in_create'), array('class' => 'col-sm-2 control-label')) !!}
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    {!! Form::radio('in_create', '0', true, array('id'=>'in_create_0', 'class' => 'form-check-input')) !!}
                                    {!! Form::label('in_create_0', trans('general/admin_lang.no'), array('class' =>
                                    'form-check-label')) !!}
                                </div>
                                <div class="form-check form-check-inline">
                                    {!! Form::radio('in_create', '1', false, array('id'=>'in_create_1', 'class' =>'form-check-input')) !!}
                                    {!! Form::label('in_create_1', trans('general/admin_lang.yes'), array('class' =>'form-check-label')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('in_edit', trans('crud-generator::fields/admin_lang.in_edit'), array('class' => 'col-sm-2 control-label')) !!}
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    {!! Form::radio('in_edit', '0', true, array('id'=>'in_edit_0', 'class' => 'form-check-input')) !!}
                                    {!! Form::label('in_edit_0', trans('general/admin_lang.no'), ['class' =>'form-check-label']) !!}
                                </div>
                                <div class="form-check form-check-inline">
                                    {!! Form::radio('in_edit', '1', false, array('id'=>'in_edit_1', 'class' =>'form-check-input')) !!}
                                    {!! Form::label('in_edit_1', trans('general/admin_lang.yes'), ['class' =>'form-check-label']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('in_show', trans('crud-generator::fields/admin_lang.in_show'), array('class' => 'col-sm-2 control-label')) !!}
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    {!! Form::radio('in_show', '0', true, array('id'=>'in_show_0', 'class' => 'form-check-input')) !!}
                                    {!! Form::label('in_show_0', trans('general/admin_lang.no'), ['class' =>'form-check-label']) !!}
                                </div>
                                <div class="form-check form-check-inline">
                                    {!! Form::radio('in_show', '1', false, array('id'=>'in_show_1', 'class' =>'form-check-input')) !!}
                                    {!! Form::label('in_show_1', trans('general/admin_lang.yes'), ['class' =>'form-check-label']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('is_required', trans('crud-generator::fields/admin_lang.is_required'), array('class' => 'col-sm-2 control-label', 'readonly' => true)) !!}
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    {!! Form::radio('is_required', '0', true, array('id'=>'is_required_0', 'class' => 'form-check-input')) !!}
                                    {!! Form::label('is_required_0', trans('general/admin_lang.no'), ['class' =>'form-check-label']) !!}
                                </div>
                                <div class="form-check form-check-inline">
                                    {!! Form::radio('is_required', '1', false, array('id'=>'is_required_1', 'class' =>'form-check-input')) !!}
                                    {!! Form::label('is_required_1', trans('general/admin_lang.yes'), ['class' =>'form-check-label']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(!empty($field->id))
                    @if(\View::exists('crud-generator::fields.partials.admin_edit_'.$field->field_type_slug))
                        @include('crud-generator::fields.partials.admin_edit_'.$field->field_type_slug)
                    @endif
                @endif

                <div class="card card-solid">

                    <div class="card-footer">

                        <a href="{{ url('/admin/crud-generator/'.$module->id.'/fields') }}" class="btn btn-default">{{ trans('general/admin_lang.cancelar') }}</a>
                        <button type="submit" class="btn btn-info float-right text-light">{{ trans('general/admin_lang.save') }}</button>

                    </div>

                </div>
                {!! Form::close() !!}
            </div>


       
    </div>

@endsection

@section('foot_page')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    @if(!empty($field->id) && ($field->field_type_slug == 'radio' ||
            $field->field_type_slug == 'select' ||
            $field->field_type_slug == 'color'||
            $field->field_type_slug == 'checkboxMulti'||
            $field->field_type_slug == 'belongsToRelationship' ||
            $field->field_type_slug == 'belongsToManyRelationship'
            ))
        @include('crud-generator::fields.partials.admin_edit_'.$field->field_type_slug.'_script')
    @endif

    {!! JsValidator::formRequest('Clavel\CrudGenerator\Requests\FieldRequest')->selector('#formData') !!}
@stop
