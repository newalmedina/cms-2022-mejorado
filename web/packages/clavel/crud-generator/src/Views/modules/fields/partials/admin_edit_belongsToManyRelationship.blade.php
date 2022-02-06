<?php

$models = Clavel\CrudGenerator\Services\ModelSelector::searchModels();

?>
<div class="card card-primary">
    <div class="card-header  with-border"><h3 class="card-title">{{ trans("crud-generator::fields/admin_lang.extra_options") }} - belongsToRelationship</h3></div>
    <div class="card-body">
        <div class="form-group row">
            {!! Form::label('default_value', trans('crud-generator::fields/admin_lang.modelo'), array('class' => 'col-sm-2 control-label')) !!}
            <div class="col-sm-10">
                <select name="default_value" id="default_value" class="form-control select2">
                    @foreach($models as $model)
                        <option value="{{ $model[1] }}" @if( $model[1]==$field->default_value) selected @endif >{{ $model[0] }}</option>
                    @endforeach

                </select>

            </div>
        </div>

        <div class="form-group row">
            {!! Form::label('data', trans('crud-generator::fields/admin_lang.data_field'), array('class' => 'col-sm-2 control-label')) !!}
            <div class="col-sm-10">
                <select name="data" id="data" class="form-control select2">
                </select>
            </div>
        </div>
    </div>
</div>
