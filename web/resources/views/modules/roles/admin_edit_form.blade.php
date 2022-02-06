{!! Form::model($role, $form_data, array('role' => 'form')) !!}
<div class="card-body">
    <div class="form-group row">
        {!! Form::label('name', trans('roles/lang.roles_nombre_corto'), array('class' => 'col-sm-2 control-label', 'readonly' => true)) !!}
        <div class="col-md-10">
            {!! Form::text('name', null, array('placeholder' => trans('roles/lang.roles_nombre_corto_insertar'), 'class' => 'form-control', 'id' => 'first_name', 'readonly' => true)) !!}
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('display_name', trans('roles/lang.nombre_roles'), array('class' => 'col-sm-2 control-label required-input')) !!}
        <div class="col-md-10">
            {!! Form::text('display_name', null, array('placeholder' => trans('roles/lang.nombre_roles_insertar'), 'class' => 'form-control', 'id' => 'first_name')) !!}
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('description', trans('roles/lang.description'), array('class' => 'col-sm-2 control-label')) !!}
        <div class="col-md-10">
            {!! Form::text('description', null, array('placeholder' => trans('roles/lang.description_insertar'), 'class' => 'form-control', 'id' => 'first_name')) !!}
        </div>
    </div>

    @if(!$role->fixed)
        <div class="form-group row">
            {!! Form::label('active', Lang::get('roles/lang._activar_roles'), array('class' => 'col-md-2 control-label required-input')) !!}
            <div class="col-md-9">
                <div class="form-check form-check-inline">
                    {!! Form::radio('active', 0, true, array('id'=>'active_0', 'class' => 'form-check-input')) !!}
                    {!! Form::label('active_0', trans('general/admin_lang.no'), array('class' => 'form-check-label')) !!}

                </div>
                <div class="form-check form-check-inline">
                    {!! Form::radio('active', 1, false, array('id'=>'active_1', 'class' => 'form-check-input')) !!}
                    {!! Form::label('active_1', trans('general/admin_lang.yes'), array('class' => 'form-check-label')) !!}
                </div>
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('active', Lang::get('roles/lang.can_select'), array('class' => 'col-md-2 control-label required-input')) !!}
            <div class="col-md-9">
                <div class="form-check form-check-inline">
                    {!! Form::radio('can_select', 0, true, ['id' => 'select_0', 'class' => 'form-check-input']) !!}
                    {!! Form::label('select_0', trans('general/admin_lang.no'), ['class' => 'form-check-label']) !!}
                </div>
                <div class="form-check form-check-inline">
                    {!! Form::radio('can_select', 1, false, ['id' => 'select_1', 'class' => 'form-check-input']) !!}
                    {!! Form::label('select_1', trans('general/admin_lang.yes'), ['class' => 'form-check-label']) !!}
                </div>
            </div>
        </div>
    @endif
</div>

<div class="card-footer">

    <a href="{{ url('/admin/roles') }}" class="btn btn-default">{{ trans('roles/lang.cancelar') }}</a>
    @if((Auth::user()->isAbleTo('admin-roles-create') && $id==0) || (Auth::user()->isAbleTo('admin-roles-update') && $id!=0))
        <button type="submit" class="btn btn-info float-right text-light">{{ trans('roles/lang.guardar') }}</button>
    @endif

</div>

{!! Form::close() !!}

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

{!! JsValidator::formRequest('App\Http\Requests\AdminRolesRequest')->selector('#formData') !!}
