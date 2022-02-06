{!! Form::model($user,  array('route' => array('profile.social.update', $user->id), 'method' => 'PATCH',
                                                        'id' => 'formData', 'class' => 'form-horizontal') ) !!}
{!! Form::hidden('id', $user->id, array('id' => 'id')) !!}
<div class="card-body">

        <div class="form-group row">
            {!! Form::label('userProfile[facebook]', Lang::get('profile/admin_lang.facebook'), array('class' => 'col-md-2 control-label')) !!}
            <div class="col-md-10">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                    </div>
                    {!! Form::text('userProfile[facebook]', null, array('placeholder' =>  Lang::get('profile/admin_lang.facebook'), 'class' => 'form-control', 'id' => 'facebook')) !!}
                </div>
            </div>
        </div>


        <div class="form-group row">
            {!! Form::label('userProfile[twitter]', Lang::get('profile/admin_lang.twitter'), array('class' => 'col-md-2 control-label')) !!}
            <div class="col-md-10">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                    </div>
                    {!! Form::text('userProfile[twitter]', null, array('placeholder' =>  Lang::get('profile/admin_lang.twitter'), 'class' => 'form-control', 'id' => 'twitter')) !!}
                </div>
            </div>
        </div>

        <div class="form-group row">
            {!! Form::label('userProfile[linkedin]', Lang::get('profile/admin_lang.linkedin'), array('class' => 'col-md-2 control-label')) !!}
            <div class="col-md-10">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                    </div>
                    {!! Form::text('userProfile[linkedin]', null, array('placeholder' =>  Lang::get('profile/admin_lang.linkedin'), 'class' => 'form-control', 'id' => 'linkedin')) !!}
                </div>
            </div>
        </div>

        <div class="form-group row">
            {!! Form::label('userProfile[youtube]', Lang::get('profile/admin_lang.youtube'), array('class' => 'col-md-2 control-label')) !!}
            <div class="col-md-10">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                    </div>
                    {!! Form::text('userProfile[youtube]', null, array('placeholder' =>  Lang::get('profile/admin_lang.youtube'), 'class' => 'form-control', 'id' => 'youtube')) !!}
                </div>
            </div>
        </div>

        <div class="form-group row">
            {!! Form::label('userProfile[bio]', trans('profile/admin_lang.bio'), array('class' => 'col-md-2 control-label')) !!}
            <div class="col-md-12">
                {!! Form::textarea('userProfile[bio]', null, array('placeholder' =>  Lang::get('profile/admin_lang.bio'), 'class' => 'form-control textarea', 'id' => 'bio')) !!}
            </div>
        </div>


</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{ trans('profile/admin_lang.guardar') }}</button>
</div>

{!! Form::close() !!}
