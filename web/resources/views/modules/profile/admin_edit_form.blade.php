{!! Form::model($user, ['role' => 'form', 'id' => 'formData', 'method' => 'POST']) !!}
<div class="card-body">
    <div class="row">
        <div class="col-lg-6 form-group">
            {!! Form::label('userProfile[first_name]', trans('profile/admin_lang._NOMBRE_USUARIO')) !!}
            {!! Form::text('userProfile[first_name]', null, array('placeholder' => trans('users/lang._INSERTAR_NOMBRE_USUARIO'), 'class' => 'form-control', 'id' => 'first_name')) !!}
        </div>
        <div class="col-lg-6 form-group">
            {!! Form::label('userProfile[last_name]', trans('profile/admin_lang._APELLIDOS_USUARIO')) !!}
            {!! Form::text('userProfile[last_name]', null, array('placeholder' => trans('users/lang._INSERTAR_APELLIDOS_USUARIO'), 'class' => 'form-control', 'id' => 'last_name')) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 form-group">
            {!! Form::label('email', Lang::get('profile/admin_lang._EMAIL_USUARIO')) !!}
            {!! Form::text('email', null, array('placeholder' =>  Lang::get('profile/admin_lang._INSERTAR_EMAIL_USUARIO'), 'class' => 'form-control')) !!}
        </div>
        <div class="col-lg-6 form-group">
            {!! Form::label('userProfile[gender]', Lang::get('profile/admin_lang._genero_sexusal')) !!}
            <div class="col-12">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" id="male" name="userProfile[gender]" type="radio" value="male" checked="checked" required />
                    <label class="form-check-label" for="male">{!! trans('profile/admin_lang.hombre') !!}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" id="female" name="userProfile[gender]" type="radio" value="female" @if ($user->userProfile->gender == 'female') checked="checked" @endif />
                    <label class="form-check-label" for="female">{!! trans('profile/admin_lang.mujer') !!}</label>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-6 form-group">
            {!! Form::label('userProfile[user_lang]', Lang::get('profile/admin_lang.idioma_usuario')) !!}
            <span class="text-danger">*</span>
            <select name="userProfile[user_lang]" class="form-control">
                @foreach(\App\Models\Idioma::where("active","=","1")->get() as $key=>$value)
                    <option value="{{ $value->code }}"
                            @if($value->code==$user->userProfile->user_lang) selected @endif>{{ $value->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <p class="lead">{{ trans('profile/admin_lang.accesos_web') }}</p>

    <div class="row">
        <div class="col-lg-6 form-group">
            {!! Form::label('username', trans('profile/admin_lang.usuario')) !!}
            {!! Form::text('username', null, array('placeholder' => trans('profile/admin_lang._INSERTAR_USUSARIO_USUARIO'), 'class' => 'form-control', 'required' => 'required')) !!}
            <div id="login_info">
                <h4 style="color: #ec3f41; font-weight: bold;">{{ trans('profile/admin_lang._NOTCORRECTUSERLOGIN') }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 form-group">
            {!! Form::label('password', trans('profile/admin_lang._CONTASENYA_USUARIO')) !!}
            {!! Form::text('password', '', array('class' => 'form-control', 'id' => 'password', 'autocomplete'=>'off')) !!}
            <div id="pswd_info">
                <h4>{{ trans('profile/admin_lang._KEY_POSIBILITIES_INFO') }}</h4>
                <ul>
                    <li id="letter"
                        class="invalid">{{ trans('profile/admin_lang._KEY_POSIBILITIES_001') }}
                        <strong>{{ trans('profile/admin_lang._KEY_POSIBILITIES_003') }}</strong>
                    </li>
                    <li id="capital"
                        class="invalid">{{ trans('profile/admin_lang._KEY_POSIBILITIES_001') }}
                        <strong>{{ trans('profile/admin_lang._KEY_POSIBILITIES_004') }}</strong>
                    </li>
                    <li id="number"
                        class="invalid">{{ trans('profile/admin_lang._KEY_POSIBILITIES_001') }}
                        <strong>{{ trans('profile/admin_lang._KEY_POSIBILITIES_005') }}</strong>
                    </li>
                    <li id="length"
                        class="invalid">{{ trans('profile/admin_lang._KEY_POSIBILITIES_002') }}
                        <strong>{{ trans('profile/admin_lang._KEY_POSIBILITIES_006') }}</strong>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-6 form-group margin">
            {!! Form::label('password_confirmation', trans('profile/admin_lang._REPETIR_CONTASENYA_USUARIO')) !!}
            {!! Form::text('password_confirmation', '', array('class' => 'form-control', 'autocomplete'=>'off')) !!}
        </div>
    </div>

</div>

<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{ trans('profile/admin_lang.guardar') }}</button>
</div>

{!! Form::close() !!}
