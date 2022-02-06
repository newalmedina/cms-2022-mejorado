{!! Form::open(array('role' => 'form','id'=>'frm_Role_User', 'method'=>'post')) !!}
{!! Form::hidden('id', $user->id, array('id' => 'id')) !!}
{!! Form::hidden('results', '', array('id' => 'results')) !!}

<?php $nPrinter=0; ?>
@foreach($roles as $key=>$value)
    @if($nPrinter==0 || $nPrinter%4==0)
        @if($nPrinter!=0) </div> @endif
        <div class="row">
    @endif

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div id="sel_{{ $value->id }}" class="card @if($user->hasRole($value->name)) card-success @endif" style="cursor: pointer;" onclick="javascript:selected('sel_{{ $value->id }}');" data-value="{{ $value->id }}">
            <div class="card-header with-border">
                <h3 class="card-title">{{ $value->display_name }}</h3>
            </div>
            <div class="card-body">
                <h5>
                    <span class="info-box-icon"><i class="fa fa-user-plus" style="font-size: 60px; background: none; height: auto; line-height: 64px;"></i></span>
                    {{ $value->description }}
                </h5>
            </div>
        </div>
    </div>

    <?php $nPrinter++; ?>

    @endforeach
    @if($nPrinter!=0) </div> @endif

{!! Form::close() !!}


<br clear="all">

<div class="card-footer">

    <a href="{{ url('/admin/users') }}" class="btn btn-default">{{ trans('users/lang.cancelar') }}</a>
    @if(Auth::user()->isAbleTo('admin-users-update'))
        <button onclick="sendInfo();" class="btn btn-info float-right text-light">{{ trans('users/lang.guardar') }}</button>
    @endif

</div>

<script>
    function selected(itemSel) {
        obj = $("#" + itemSel);

        if(obj.hasClass("card-success")) {
            obj.removeClass("card-success");
            obj.addClass("card-default");
        } else {
            obj.removeClass("card-default");
            obj.addClass("card-success");
        }
    }

    function sendInfo() {
        var sendUrlId = "";

        $("#frm_Role_User").attr("action", "{!! url("admin/users/roles/update") !!}");

        $("#frm_Role_User .card-success").each(function() {
            if(sendUrlId!='') sendUrlId+=",";
            sendUrlId+=$(this).attr("data-value");
        });

        if(sendUrlId!='') {
            $("#results").val(sendUrlId);
            $("#frm_Role_User").submit();
        } else {
            $("#modal_alert").addClass('modal-warning');
            $("#alertModalBody").html("<i class='fas fa-times-circle text-danger' style='font-size: 64px; float: left; margin-right:15px;'></i> {!! trans('users/lang.seleccione_un_rol') !!}");
            $("#modal_alert").modal('toggle');
        }

    }
</script>
