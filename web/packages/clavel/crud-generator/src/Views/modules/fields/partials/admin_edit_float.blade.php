<div class="card card-primary">
    <div class="card-header  with-border"><h3 class="card-title">{{ trans("crud-generator::fields/admin_lang.extra_options") }} - Float</h3></div>
    <div class="card-body">
        <div class="form-group row">
            {!! Form::label('min_length', trans('crud-generator::fields/admin_lang.min_length'), array('class' => 'col-sm-2 control-label')) !!}
            <div class="col-sm-10">
                {!! Form::text('min_length', null,
                    array('placeholder' => trans('crud-generator::fields/admin_lang.min_length'),
                    'class' => 'form-control input-xlarge',
                    'id' => 'min_length')) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('max_length', trans('crud-generator::fields/admin_lang.max_length'), array('class' => 'col-sm-2 control-label')) !!}
            <div class="col-sm-10">
                {!! Form::text('max_length', null,
                    array('placeholder' => trans('crud-generator::fields/admin_lang.max_length'),
                    'class' => 'form-control input-xlarge',
                    'id' => 'max_length')) !!}
            </div>
        </div>

        <div class="form-group row">
            {!! Form::label('float_accuracy', trans('crud-generator::fields/admin_lang.float_accuracy'), array('class' => 'col-sm-2 control-label')) !!}
            <div class="col-sm-10">
                {!! Form::text('float_accuracy', null,
                    array('placeholder' => trans('crud-generator::fields/admin_lang.float_accuracy'),
                    'class' => 'form-control input-xlarge',
                    'id' => 'float_accuracy')) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('float_length', trans('crud-generator::fields/admin_lang.float_length'), array('class' => 'col-sm-2 control-label')) !!}
            <div class="col-sm-10">
                {!! Form::text('float_length', null,
                    array('placeholder' => trans('crud-generator::fields/admin_lang.float_length'),
                    'class' => 'form-control input-xlarge',
                    'id' => 'float_length')) !!}
            </div>
        </div>


    </div>
</div>
