{!! Form::model($user, $form_data, ['role' => 'form']) !!}
{!! Form::hidden('id', $user->id, ['id' => 'id']) !!}

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ Lang::get('users/lang.create_api_token') }}</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">

                <p>{{ Lang::get('users/lang.create_api_token_info') }}</p>
                <div class="form-group">
                    {!! Form::label('api_token', Lang::get('users/lang.token_name'), ['class' => 'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            {!! Form::text('api_token', null, ['placeholder' => Lang::get('users/lang.token_name'), 'class' => 'form-control', 'id' => 'api_token']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a href="{{ url('/admin/users') }}" class="btn btn-default">{{ trans('users/lang.cancelar') }}</a>


        @if ((Auth::user()->isAbleTo('admin-users-create') && $id == 0) || (Auth::user()->isAbleTo('admin-users-update') && $id != 0))
            <button type="submit"
                class="btn btn-info float-right text-light">{{ trans('users/lang.guardar') }}</button>
        @endif
    </div>
    <!-- /.card-footer-->
</div>


{!! Form::close() !!}


<div class="card card-success card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ Lang::get('users/lang.manage_api_tokens') }}</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <p>{{ Lang::get('users/lang.manage_api_tokens_info') }}</p>

        @if (!empty($tokens_list))
            <table class="table table-condensed">
                <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>{{ Lang::get('users/lang.token_name') }}</th>
                        <th>{{ Lang::get('users/lang.token') }}</th>
                        <th style="width: 40px"></th>
                    </tr>

                    @foreach ($tokens_list as $t)
                        <tr>
                            <td>{{ $loop->index + 1 }}.</td>
                            <td> {{ $t->name }}</td>
                            <td><a href="#" onclick="showToken({{ $loop->index + 1 }});">
                                    <i id="token_fa_{{ $loop->index + 1 }}" class="fa fa-eye"
                                        aria-hidden="true"></i>
                                </a>
                                &nbsp;
                                <span id="token_{{ $loop->index + 1 }}" style="visibility: hidden;">

                                    <span id="token_data_{{ $loop->index + 1 }}">
                                        {{ $t->token }}
                                    </span>

                                    <a href="#" onclick="copyToken({{ $loop->index + 1 }});">
                                        <i class="fa fa-clipboard" aria-hidden="true"></i>
                                    </a>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm"
                                    onclick="javascript:deleteElement({{ $t->id }});"
                                    data-content="Borrar registro" data-placement="right" data-toggle="popover"
                                    data-original-title="" title="">
                                    <i class="fa fa-trash" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning alert-dismissible">
                <h4><i class="icon fa fa-warning"></i> No hay tokens actualmente</h4>

            </div>
        @endif

    </div>
    <!-- /.box-body -->

</div>

<form id="delete-form" action="{{ route('admin.users.api.destroy', [$user->id]) }}" method="POST"
    style="display: none;">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="delete" />
    <input type="hidden" name="token_id" id="token_id" value="" />
</form>


<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

<script>
    function deleteElement(id) {
        var strBtn = "";

        $("#confirmModalLabel").html("{{ trans('users/lang.user_warning_title') }}");
        $("#confirmModalBody").html("{{ trans('users/lang.token_delete_question') }}");
        strBtn +=
            '<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general/admin_lang.close') }}</button>';
        strBtn += '<button type="button" class="btn btn-primary" onclick="javascript:deleteinfo(\'' + id +
            '\');">{{ trans('general/admin_lang.borrar_item') }}</button>';
        $("#confirmModalFooter").html(strBtn);
        $('#modal_confirm').modal('toggle');
    }

    function deleteinfo(id) {
        $("#token_id").val(id);
        $("#delete-form").submit();
        return false;
    }

    function showToken(id) {
        if ($('#token_' + id).css('visibility') == 'hidden') {
            $('#token_' + id).css('visibility', 'visible');
        } else {
            $('#token_' + id).css('visibility', 'hidden');
        }

        $('#token_fa_' + id).toggleClass('fa-eye fa-eye-slash')

        return false;
    }

    function copyToken(id) {
        /* Get the text field */
        var copyText = $('#token_data_' + id).html();

        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = copyText; //save main text in it
        sampleTextarea.select(); //select textarea contenrs
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);

        /* Alert the copied text */
        alert("Token copiado en el portapapeles");
    }
</script>

{!! JsValidator::formRequest('App\Http\Requests\AdminApiTokenRequest')->selector('#formDataApi') !!}
