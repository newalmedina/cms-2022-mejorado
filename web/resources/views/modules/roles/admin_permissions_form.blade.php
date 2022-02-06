<link href="{{ asset('/assets/admin/vendor/jquery-bonsai/css/jquery.bonsai.css')}}" rel="stylesheet" />


{!! Form::open(array('role' => 'form','id'=>'frm_Permission_Role', 'method'=>'post')) !!}
{!! Form::hidden('id', $id, array('id' => 'id')) !!}
{!! Form::hidden('results', '', array('id' => 'results')) !!}

<div class="row">

    <div class="col-lg-12">
        <p>{{ trans('roles/lang.informacion_permisos') }}</p>

        {!! "<ol id='checkboxes'>" !!}
        <?php $actDepth = 0; ?>

        @foreach($permissionsTree as $key=>$value)

            @if($actDepth!=$value->depth)
                @if($actDepth>$value->depth)
                    @for($nX=$actDepth;$nX>$value->depth; $nX--)
                    </ol>
                    </li>
                    @endfor
                @endif
            <?php $actDepth=$value->depth; ?>
            @endif

            @if($value->depth==0)
                {!! "<li class='expanded'>" !!}
            @else
                {!! "<li>" !!}
            @endif

            @if($value->isRoot())
                <input type='checkbox' id="root" value='root' />
                <i class="fas fa-folder text-yellow" style="font-size: 18px; margin-left: 5px; margin-right: 5px;"></i>
                {{ trans('roles/lang.todos') }}
                @if($value->descendants()->count()>0)
                    {!! "<ol>" !!}
                @else
                    {!! "</li>" !!}
                @endif
            @else
                @if($value->descendants()->count()>0)
                    <input type='checkbox' value="{{ $value->permission["id"] }}" @if(in_array($value->permission["id"], $a_arrayPermisos)) checked @endif />
                    <i class="fas fa-folder text-yellow text-tree-icon"></i>

                    @if(config("general.admin.allow_remove_permission_tree", false))
                    <a href="#" onclick="javascript:deleteElement('{{ url('admin/roles/permissions/'.$id.'/'.$value->permission["id"]) }}');" >
                        <i class="fas fa-trash text-danger text-tree-icon"></i>
                    </a>
                    @endif

                    {{ $value->permission["display_name"] }}
                    {!! "<ol>" !!}
                @else
                    <input type='checkbox' value='{{ $value->permission["id"] }}' @if(in_array($value->permission["id"], $a_arrayPermisos)) checked @endif />
                    <i class="fas fa-key text-green text-tree-icon-med"></i>

                    @if(config("general.admin.allow_remove_permission_tree", false))
                    <a href="#" onclick="javascript:deleteElement('{{ url('admin/roles/permissions/'.$id.'/'.$value->permission["id"]) }}');" >
                        <i class="fas fa-trash text-danger text-tree-icon"></i>
                    </a>
                    @endif

                    {{ $value->permission["display_name"] }}&nbsp;<span class="text-tree-min">[{{ $value->permission["name"]  }}]</span>
                    {!! "</li>" !!}
                @endif
            @endif





        @endforeach

        @if($actDepth>0)
            @for($nX=$actDepth;$nX>0; $nX--)
                {!! "</ol>" !!}
                {!! "</li>" !!}
            @endfor
        @endif

        {!! "</ol>" !!}

    </div>
</div>

{!! Form::close() !!}

<br clear="all">

<div class="card-footer">

    <a href="{{ url('/admin/roles') }}" class="btn btn-default">{{ trans('roles/lang.cancelar') }}</a>
    @if(Auth::user()->isAbleTo('admin-roles-update'))
        <button onclick="sendInfo();" class="btn btn-info float-right text-light">{{ trans('roles/lang.guardar') }}</button>
    @endif

</div>


<script type="text/javascript" src="{{ asset('/assets/admin/vendor/jquery-bonsai/js/jquery.bonsai.js')}}"></script>
<script type="text/javascript" src="{{ asset('/assets/admin/vendor/jquery-qubit/js/jquery.qubit.js')}}"></script>

<script>
    $(document).ready(function() {

        $('#checkboxes').bonsai({
            expandAll: false,
            checkboxes: true
        });

    });

    function sendInfo() {
        var sendUrlId = "";

        $("#frm_Permission_Role").attr("action", "{!! url("admin/roles/permissions/update") !!}");


        $("#checkboxes input").each(function() {
            if(($(this).val()!='' && $(this).attr("id")!='root') && ($(this).is(":checked") || $(this).is(":indeterminate"))) {
                if(sendUrlId!='') sendUrlId+=",";
                sendUrlId+=$(this).val();
            }
        });

        if(sendUrlId!='') {
            $("#results").val(sendUrlId);
            $("#frm_Permission_Role").submit();
        } else {
            $("#modal_alert").addClass('modal-warning');
            $("#alertModalBody").html("<i class='fas fa-times-circle text-danger' style='font-size: 64px; float: left; margin-right:15px;'></i> {!! trans('roles/lang.seleccione_un_permiso') !!}");
            $("#modal_alert").modal('toggle');
        }

    }

    function deleteElement(url) {
            var strBtn = "";

            $("#confirmModalLabel").html("{{ trans('general/admin_lang.warning_title') }}");
            $("#confirmModalBody").html("{{ trans('general/admin_lang.delete_question') }}");
            strBtn+= '<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general/admin_lang.close') }}</button>';
            strBtn+= '<button type="button" class="btn btn-primary" onclick="javascript:deleteinfo(\''+url+'\');">{{ trans('general/admin_lang.borrar_item') }}</button>';
            $("#confirmModalFooter").html(strBtn);
            $('#modal_confirm').modal('toggle');
        }

        function deleteinfo(url) {
            $.ajax({
                url     : url,
                type    : 'POST',
                "headers": {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                data: {_method: 'delete'},
                success : function(data) {
                    console.log(data);
                    var strBtn = "";
                    $('#modal_confirm').modal('hide');
                    if(data) {
                        $("#modal_alert").addClass('modal-success');
                        $("#alertModalHeader").html("Borrado de permisos");
                        $("#alertModalBody").html("<i class='fa fa-check-circle' style='font-size: 64px; float: left; margin-right:15px;'></i> " + data.msg);

                        strBtn+= '<button type="button" class="btn btn-success" onclick="javascript:window.location.reload(false);">{{ trans('general/admin_lang.close') }}</button>';
                        $("#alertModalFooter").html(strBtn);

                        $("#modal_alert").modal('toggle');

                    } else {
                        $("#modal_alert").addClass('modal-danger');
                        $("#alertModalBody").html("<i class='fa fa-bug' style='font-size: 64px; float: left; margin-right:15px;'></i> {{ trans('general/admin_lang.errorajax') }}");
                        $("#modal_alert").modal('toggle');
                    }
                    return false;
                }

            });
            return false;
        }
</script>
