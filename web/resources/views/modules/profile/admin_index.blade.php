@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')
    <style>
        .btn-toolbar .btn {
            margin-right: 40px;
        }

        .btn-toolbar .btn:last-child {
            margin-right: 0;
        }

        #fileOutput {
            min-height: 150px;
            border: dashed 2px #C0C0C0;
            background-color: #F4F4F4;
            line-height: 150px;
            text-align: center;
        }

    </style>
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')
    @include('admin.includes.errors')
    @include('admin.includes.success')

    <div class='row'>

        <div class='col-md-2'>
            <div id="fileOutput">
                @if (!empty($user->userProfile->photo))
                    <img id='image_ouptup' src="{{ url('admin/profile/getphoto/' . $user->userProfile->photo) }}"
                        class="img-circle" style="width: 80%; margin: auto;" alt="User Image" />
                @else
                    <i class="fas fa-camera" aria-hidden="true"></i> {{ trans('profile/admin_lang.sin_foto') }}
                @endif
            </div>
            <div class="mt-2 text-center">
                <h3>{{ $user->userProfile->fullName }}</h3>
            </div>

            <div class="mt-2">
                <strong>{{ trans('profile/admin_lang.miembro_desde') }}</strong>: <span class="float-right">{{ $user->CreatedAtFormatted }}</span><br>
                @if(!empty($datos_acceso))
                    <strong>Último acceso</strong>: <span class="float-right">{{ empty($datos_acceso->login_at)?'':$datos_acceso->login_at->format('d/m/Y H:i:s') }}</span><br>
                    <strong>Última IP</strong>: <span class="float-right">{{ $datos_acceso->ip_address }}</span><br>

                    @if(!empty($agent))
                        <strong>Plataforma: </strong>: <span class="float-right">{{ $agent->platform() }}</span><br>
                        <strong>Navegador: </strong>: <span class="float-right">{{ $agent->browser() }}</span><br>
                    @endif
                @endif

            </div>

        </div><!-- /.col -->

        <div class='col-md-10'>

            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item active">
                            <a id="tab_1" class="nav-link active" role="tab" data-toggle="tabajax" href="#tab_1-1"
                                data-target="#tab_1-1" aria-controls="tab_1-1" aria-selected="true">
                                {{ trans('profile/admin_lang.info_perfil') }}
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a id="tab_2" class="nav-link" role="tab" data-toggle="tabajax" href="#tab_2-2"
                                data-target="#tab_2-2" aria-controls="tab_2-2" aria-selected="true">
                                {{ trans('profile/admin_lang.security') }}
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a id="tab_3" class="nav-link" role="tab" data-toggle="tabajax" href="#tab_3-3"
                                data-target="#tab_3-3" aria-controls="tab_3-3" aria-selected="true">
                                {{ trans('profile/admin_lang.avatar') }}
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a id="tab_4" class="nav-link" role="tab" data-toggle="tabajax" href="#tab_4-4"
                                data-target="#tab_4-4" aria-controls="tab_4-4" aria-selected="true">
                                {{ trans('profile/admin_lang.social_title') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="tab_tabContent">

                        <div id="tab_1-1" class="tab-pane fade show active" role="tabpanel" aria-labelledby="tab_1">
                            @include('modules.profile.admin_edit_form')
                        </div><!-- /.tab-pane -->

                        <div id="tab_2-2" class="tab-pane fade show" role="tabpanel" aria-labelledby="tab_2">
                            @include('modules.profile.admin_two_factor_form')
                        </div><!-- /.tab-pane -->

                        <div id="tab_3-3" class="tab-pane fade show" role="tabpanel" aria-labelledby="tab_3">
                            @include('modules.profile.admin_avatar_form')
                        </div><!-- /.tab-pane -->

                        <div id="tab_4-4" class="tab-pane fade show" role="tabpanel" aria-labelledby="tab_4">
                            @include('modules.profile.admin_social_form')
                        </div>

                    </div><!-- /.tab-content -->
                </div>

            </div>
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('foot_page')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

    <script>
        var pwd1 = true;
        var login = true;

        $(document).ready(function() {
            $(document).ready(function() {
                $('[data-toggle="tabajax"]').click(function(e) {
                    loadTab($(this));
                    return false;
                });

                @if (Session::get('tab', '') != '')
                    loadTab($("#{!! Session::get('tab') !!}"));
                @else
                    loadTab($("#tab_1"));
                @endif

            });



            $("#username").change(function() {

                $.ajax({
                    url: "{{ url('/admin/profile/exists/login') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}'
                    },
                    data: {
                        user_id: '{{ $user->id }}',
                        login: $("#username").val()
                    },
                    success: function(data) {
                        if (data == 'NOK') {
                            login = false;
                            $('#login_info').fadeIn(500);
                        } else {
                            login = true;
                            $('#login_info').fadeOut(500);
                        }
                    }
                });

            });

            if ($("#password").val() != '') pwd1 = checkform($("#password").val());

            $('#password').keyup(function() {

                pwd1 = checkform($(this).val());

            }).blur(function() {

                $('#pswd_info').fadeOut(500);

            });

            $("#formData").submit(function(event) {

                if (!pwd1 && ($("#password").val() != '')) {
                    return checkform($("#password").val());
                }

                if (!login) {
                    return false;
                }

            });


            $("#profile_image").change(function() {
                getFileName();
                readURL(this);
            });

            $("#remove").click(function() {
                $('#nombrefichero').val('');
                $('#profile_image').val("");
                $('#fileOutput').html(
                    '<i class="fas fa-camera" aria-hidden="true"></i> {{ trans('profile/admin_lang.sin_foto') }}'
                );
                $("#remove").css("display", "none");
                $("#delete_photo").val('1');
            });
        });

        function loadTab(obj) {
            var $this = obj,
                loadurl = $this.attr('href'),
                targ = $this.attr('data-target');

            $this.tab('show');
        }

        function checkform(pswd) {
            var pswdlength = false;
            var pswdletter = false;
            var pswduppercase = false;
            var pswdnumber = false;

            if (pswd.length >= 7) {
                $('#length').removeClass('invalid').addClass('valid');
                pswdlength = true;
            } else {
                $('#length').removeClass('valid').addClass('invalid');
            }

            if (pswd.match(/[A-z]/)) {
                $('#letter').removeClass('invalid').addClass('valid');
                pswdletter = true;
            } else {
                $('#letter').removeClass('valid').addClass('invalid');
            }

            if (pswd.match(/[A-Z]/)) {
                $('#capital').removeClass('invalid').addClass('valid');
                pswduppercase = true;
            } else {
                $('#capital').removeClass('valid').addClass('invalid');
            }

            if (pswd.match(/\d/)) {
                $('#number').removeClass('invalid').addClass('valid');
                pswdnumber = true;
            } else {
                $('#number').removeClass('valid').addClass('invalid');
            }

            if (pswdlength && pswdletter && pswduppercase && pswdnumber) {
                $('#pswd_info').fadeOut(500);
                return true;
            } else {
                $('#pswd_info').fadeIn(500);
                return false;
            }
        }

        function updloadAvatar() {
            $("#formAvatar").attr("action", "{!! url('admin/profile/photo') !!}");
            $("#formAvatar").submit();
        }

        function getFileName() {
            $('#nombrefichero').val($('#profile_image')[0].files[0].name);
            $("#delete_photo").val('1');
        }

        function readURL(input) {


            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#fileOutput').html("<img src='' id='image_ouptup' width='100%' alt=''>");
                    $("#remove").css("display", "block");
                    $('#image_ouptup').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    {!! JsValidator::formRequest('App\Http\Requests\AdminProfileRequest')->selector('#formData') !!}
@stop
