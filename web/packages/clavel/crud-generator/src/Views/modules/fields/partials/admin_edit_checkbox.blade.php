<div class="card card-primary">
    <div class="card-header  with-border"><h3 class="card-title">{{ trans("crud-generator::fields/admin_lang.extra_options") }} - Checkbox/Visible</h3></div>
    <div class="card-body">
        <div class="form-group row">
            {!! Form::label('default_value', trans('crud-generator::fields/admin_lang.default_value'), array('class' => 'col-sm-2 control-label')) !!}
            <div class="col-sm-10">
                <select name="default_value" id="default_value" class="form-control select2">

                    <option value="is_unchecked" @if('is_unchecked'==$field->default_value) selected @endif>Desmarcado por defecto</option>
                    <option value="is_checked" @if('is_checked'==$field->default_value) selected @endif>Marcado por defecto</option>

                </select>

            </div>
        </div>
    </div>
</div>
