<div class="card card-primary">
    <div class="card-header  with-border"><h3 class="card-title">{{ trans("crud-generator::fields/admin_lang.extra_options") }} - Color</h3></div>
    <div class="card-body">
        <div class="form-group row">
            {!! Form::label('default_value', trans('crud-generator::fields/admin_lang.color'), array('class' => 'col-sm-2 control-label')) !!}
            <div class="col-sm-10">
                <div class="input-group default_value_colorpicker colorpicker-element">
                    {!! Form::text('default_value', null, array('placeholder' => trans('crud-generator::fields/admin_lang.select_color'), 'class' => 'form-control', 'id' => 'default_value')) !!}
                    <div class="input-group-prepend">
                        <span class="input-group-text"><em style="background-color: rgb(136, 119, 119);"></em></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
