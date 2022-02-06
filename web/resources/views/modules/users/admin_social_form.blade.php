{!! Form::model($user, $form_data, ['role' => 'form']) !!}
{!! Form::hidden('id', $user->id, ['id' => 'id']) !!}
<div class="card-body">

    <div class="form-group row">
        {!! Form::label('userProfile[facebook]', Lang::get('users/lang.facebook'), ['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-10">
            <div class="input-group">
                 <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                </div>
                {!! Form::text('userProfile[facebook]', null, array('placeholder' =>  Lang::get('users/lang.facebook'), 'class' => 'form-control', 'id' => 'facebook')) !!}
            </div>
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('userProfile[twitter]', Lang::get('users/lang.twitter'), ['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-10">
            <div class="input-group">
                 <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                </div>
                {!! Form::text('userProfile[twitter]', null, array('placeholder' =>  Lang::get('users/lang.twitter'), 'class' => 'form-control', 'id' => 'twitter')) !!}
            </div>
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('userProfile[linkedin]', Lang::get('users/lang.linkedin'), ['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-10">
            <div class="input-group">
                 <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                </div>
                {!! Form::text('userProfile[linkedin]', null, array('placeholder' =>  Lang::get('users/lang.linkedin'), 'class' => 'form-control', 'id' => 'linkedin')) !!}
            </div>
        </div>
    </div>


    <div class="form-group row">
        {!! Form::label('userProfile[youtube]', Lang::get('users/lang.youtube'), ['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-10">
            <div class="input-group">
                 <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                </div>
                {!! Form::text('userProfile[youtube]', null, array('placeholder' =>  Lang::get('users/lang.youtube'), 'class' => 'form-control', 'id' => 'youtube')) !!}
            </div>
        </div>
    </div>

    <div class="form-group row">
        {!! Form::label('userProfile[bio]', trans('users/lang.bio'), ['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-10">
            {!! Form::textarea('userProfile[bio]', null, ['placeholder' => Lang::get('users/lang.bio'), 'class' => 'form-control textarea', 'id' => 'bio']) !!}
        </div>
    </div>

</div>

<div class="card-footer">

    <a href="{{ url('/admin/users') }}" class="btn btn-default">{{ trans('users/lang.cancelar') }}</a>
    @if ((Auth::user()->isAbleTo('admin-users-create') && $id == 0) || (Auth::user()->isAbleTo('admin-users-update') && $id != 0))
        <button type="submit" class="btn btn-info float-right text-light">{{ trans('users/lang.guardar') }}</button>
    @endif

</div>

{!! Form::close() !!}

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

<script>
    $(document).ready(function() {

    });

</script>

{!! JsValidator::formRequest('App\Http\Requests\AdminUsersSocialRequest')->selector('#formData') !!}
