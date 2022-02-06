<div class="card card-primary">
    <div class="card-header  with-border"><h3 class="card-title">{{ trans("crud-generator::fields/admin_lang.extra_options") }} - Textarea</h3></div>
    <div class="card-body">
        <div class="form-group row">
            {!! Form::label('is_multilang', trans('crud-generator::fields/admin_lang.is_multilang'), array('class' => 'col-sm-2 control-label', 'readonly' => true)) !!}
            <div class="col-md-10">
                <div class="form-check form-check-inline">
                    {!! Form::radio('is_multilang', '0', true, array('id'=>'is_multilang_0', 'class' => 'form-check-input')) !!}
                    {!! Form::label('is_multilang_0', trans('general/admin_lang.no'), ['class' =>'form-check-label']) !!}
                </div>
                <div class="form-check form-check-inline">
                    {!! Form::radio('is_multilang', '1', false, array('id'=>'is_multilang_1', 'class' =>'form-check-input')) !!}
                    {!! Form::label('is_multilang_1', trans('general/admin_lang.yes'), ['class' =>'form-check-label']) !!}
                </div>
            </div>
            
        </div>

        <div class="form-group row">
            {!! Form::label('use_editor', trans('crud-generator::fields/admin_lang.use_editor'), array('class' => 'col-sm-2 control-label')) !!}
            <div class="col-sm-10">
                <select name="use_editor" id="use_editor" class="form-control select2">

                    <option value="no" @if('no'==$field->use_editor) selected @endif>No</option>
                    <option value="tiny" @if('tiny'==$field->use_editor) selected @endif>Tiny Editor</option>

                </select>

            </div>
        </div>
    </div>
</div>
