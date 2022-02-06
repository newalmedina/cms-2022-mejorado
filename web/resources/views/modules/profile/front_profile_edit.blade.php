@extends('modules.profile.front_index')

@section('tab_head')

@stop


@section('tab_content_1')
{!! Form::model($user, ['role' => 'form', 'id' => 'formData', 'method' => 'POST', 'files'=>true, 'class' => "p-3"]) !!}
{!! Form::hidden('delete_photo', 0, array('id' => 'delete_photo')) !!}
    <h4 class="mb-3">Información personal</h4>
    <p>{{ trans('profile/front_lang.perfil_usuario_desc') }}</p>

    <div class="row form-group">
        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('userProfile[first_name]', trans('profile/front_lang._NOMBRE_USUARIO')) !!} <span class="text-danger">*</span>
                {!! Form::text('userProfile[first_name]', null, array('placeholder' => trans('users/lang._INSERTAR_NOMBRE_USUARIO'), 'class' => 'form-control', 'id' => 'first_name')) !!}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('userProfile[last_name]', trans('profile/front_lang._APELLIDOS_USUARIO')) !!} <span class="text-danger">*</span>
                {!! Form::text('userProfile[last_name]', null, array('placeholder' => trans('users/lang._INSERTAR_APELLIDOS_USUARIO'), 'class' => 'form-control', 'id' => 'last_name')) !!}
            </div>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('email', Lang::get('profile/front_lang._EMAIL_USUARIO')) !!} <span class="text-danger">*</span>
        {!! Form::text('email', null, array('placeholder' =>  Lang::get('profile/front_lang._INSERTAR_EMAIL_USUARIO'), 'class' => 'form-control')) !!}
    </div>

    <div class="row form-group">
{{--                                 <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('userProfile[user_lang]', Lang::get('profile/front_lang._USER_LANG')) !!} <span class="text-danger">*</span>
                <select name="userProfile[user_lang]" class="form-control">
                    @foreach(\App\Models\Idioma::where("active","=","1")->get() as $key=>$value)
                        <option value="{{ $value->code }}" @if($value->code==$user->userProfile->user_lang) selected @endif>{{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
        </div> --}}
        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('profile_image', Lang::get('profile/front_lang._USER_PHOTO')) !!}
                {!! Form::file('profile_image[]',array('id'=>'profile_image', 'multiple'=>false, 'style' => 'opacity: 0; width: 0;')) !!}
                <div class="input-group">
                    <input type="text" class="form-control" id="nombrefichero" readonly>
                    <span class="input-group-append">
                        <button id="btnSelectImage" class="btn btn-primary" type="button">{{ trans('profile/front_lang.search_logo') }}</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-3">Datos de acceso</h4>

    <div class="row form-group">
        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('username', trans('profile/front_lang.usuario')) !!} <span class="text-danger">*</span>
                {!! Form::text('username', null, array('placeholder' => trans('profile/front_lang._INSERTAR_USUSARIO_USUARIO'), 'class' => 'form-control', 'required' => 'required')) !!}
                <div id="login_info">
                    <h4 style="color: #ec3f41; font-weight: bold;">{{ trans('profile/front_lang._NOTCORRECTUSERLOGIN') }}</h4>
                </div>
            </div>
        </div>

    </div>

    <div class="row form-group">
        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('password', trans('profile/front_lang._CONTASENYA_USUARIO')) !!}
                {!! Form::text('password', '', array('class' => 'form-control', 'id' => 'password', 'autocomplete'=>'off')) !!}
                <div id="pswd_info">
                    <h4>{{ trans('profile/front_lang._KEY_POSIBILITIES_INFO') }}</h4>
                    <ul>
                        <li id="letter" class="invalid">{{ trans('profile/front_lang._KEY_POSIBILITIES_001') }} <strong>{{ trans('profile/front_lang._KEY_POSIBILITIES_003') }}</strong></li>
                        <li id="capital" class="invalid">{{ trans('profile/front_lang._KEY_POSIBILITIES_001') }} <strong>{{ trans('profile/front_lang._KEY_POSIBILITIES_004') }}</strong></li>
                        <li id="number" class="invalid">{{ trans('profile/front_lang._KEY_POSIBILITIES_001') }} <strong>{{ trans('profile/front_lang._KEY_POSIBILITIES_005') }}</strong></li>
                        <li id="length" class="invalid">{{ trans('profile/front_lang._KEY_POSIBILITIES_002') }}  <strong>{{ trans('profile/front_lang._KEY_POSIBILITIES_006') }}</strong></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('password_confirmation', trans('profile/front_lang._REPETIR_CONTASENYA_USUARIO')) !!}
                {!! Form::text('password_confirmation', '', array('class' => 'form-control', 'autocomplete'=>'off')) !!}
            </div>
        </div>
    </div>

    <hr class="dotted tall">


    <div class="form-row">
        <div class="col-md-12 text-right mt-3">
            <button type="submit" class="btn btn-primary modal-confirm">{{ trans('profile/front_lang.guardar') }}</button>
        </div>
    </div>

{!! Form::close() !!}

@endsection


@section('tab_foot')

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    <script>
        var pwd1 = true;
        var login = true;

        $(document).ready(function() {

            $("#username").change(function() {

                $.ajax({
                    url: "{{url('/profile/exists/login')}}",
                    type: "POST",
                    headers: { 'X-CSRF-Token': '{{ csrf_token() }}' },
                    data: {
                        user_id: '{{ $user->id }}',
                        login: $("#username").val()
                    },
                    success       : function ( data ) {
                        if(data=='NOK') {
                            login = false;
                            $('#login_info').fadeIn(500);
                        } else {
                            login = true;
                            $('#login_info').fadeOut(500);
                        }
                    }
                });

            });

            if($("#password").val()!='') pwd1  = checkform($("#password").val());

            $('#password').keyup(function() {

                pwd1 = checkform($(this).val());

            }).blur(function() {

                $('#pswd_info').fadeOut(500);

            });

            $("#formData").submit(function( event ) {

                if(!pwd1 && ($("#password").val()!='')) {
                    return checkform($("#password").val());
                }

                if(!login) {
                    return false;
                }

            });

            $("#btnSelectImage").click(function() {
                $('#profile_image').trigger('click');
            });

            $("#profile_image").change(function(){
                getFileName();
                readURL(this);
            });

            $("#remove").click(function() {
                $('#nombrefichero').val('');
                $('#profile_image').val("");
                $('#fileOutput').html('<img src="{{ asset("/assets/front/img/!logged-user.jpg") }}" class="rounded img-fluid" alt="{{ Auth::user()->userProfile->fullName }}">');
                $("#remove").css("display","none");
                $("#delete_photo").val('1');
            });
        });

        function checkform(pswd) {
            var pswdlength 		= false;
            var pswdletter 		= false;
            var pswduppercase 	= false;
            var pswdnumber 		= false;

            if ( pswd.length >= 7 ) {
                $('#length').removeClass('invalid').addClass('valid');
                pswdlength=true;
            } else {
                $('#length').removeClass('valid').addClass('invalid');
            }

            if ( pswd.match(/[A-z]/) ) {
                $('#letter').removeClass('invalid').addClass('valid');
                pswdletter=true;
            } else {
                $('#letter').removeClass('valid').addClass('invalid');
            }

            if ( pswd.match(/[A-Z]/) ) {
                $('#capital').removeClass('invalid').addClass('valid');
                pswduppercase=true;
            } else {
                $('#capital').removeClass('valid').addClass('invalid');
            }

            if ( pswd.match(/\d/) ) {
                $('#number').removeClass('invalid').addClass('valid');
                pswdnumber=true;
            } else {
                $('#number').removeClass('valid').addClass('invalid');
            }

            if( pswdlength && pswdletter && pswduppercase && pswdnumber){
                $('#pswd_info').fadeOut(500);
                return true;
            }else{
                $('#pswd_info').fadeIn(500);
                return false;
            }
        }

        function getFileName() {
            $('#nombrefichero').val($('#profile_image')[0].files[0].name);
            $("#delete_photo").val('1');
            $("#contenedor-remove").css("display","");
        }

        function readURL(input) {


            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#fileOutput').html("<img src='' id='image_ouptup' width='100%' alt=''>");
                    $("#remove").css("display","block");
                    $('#image_ouptup').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    {!! JsValidator::formRequest('App\Http\Requests\FrontProfileRequest')->selector('#formData') !!}



@stop
