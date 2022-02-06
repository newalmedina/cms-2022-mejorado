{!! Form::open(array('role' => 'form','id'=>'formAvatar', 'method'=>'POST', 'files'=>true)) !!}
{!! Form::hidden('delete_photo', 0, array('id' => 'delete_photo')) !!}
<div class="card-body">

    <div class="form-group row">
        {!! Form::label('profile_image', Lang::get('profile/admin_lang._USER_PHOTO')) !!}
        <div class="input-group">
            <input type="text" class="form-control" id="nombrefichero" readonly>
            <span class="input-group-btn">
                <div class="btn btn-primary btn-file">
                    {{ trans('profile/admin_lang.search_avatar') }}
                    {!! Form::file('profile_image[]',array('id'=>'profile_image', 'multiple'=>true)) !!}
                </div>
            </span>
        </div>
    </div>

</div>
<div class="card-footer">
    <button onclick="updloadAvatar();"  class="btn btn-primary">{{ trans('profile/admin_lang.guardar') }}</button>
    <a id="remove" href="#" class="btn btn-danger float-right" style="@if($user->userProfile->photo=='') display: none; @endif cursor: pointer;">
        {{ trans("profile/admin_lang.remove_avatar") }}
    </a>
</div>
{!! Form::close() !!}
