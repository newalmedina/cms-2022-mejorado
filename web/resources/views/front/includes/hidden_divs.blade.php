

<div id="modalCenters" class="modal fade bs-changecenter-modal-sm" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-top: 0;">{{ trans("Centers::centers/front_lang.cambiar_centro") }}</h4>
            </div>

            <div id="changecenter_form" class="modal-body"></div>

            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-12" style="padding: 0 30px 20px 30px;">
                        <button  id="saveChangeCenter" onclick="javascript:$('#seleccion_centro').submit();" class="btn btn-primary pull-right">{{ trans("Centers::centers/front_lang.cambiar") }}</button>
                        <button type="button" class=" btn btn-dark" data-bs-dismiss="modal" aria-label="Close">{{ trans("Centers::centers/front_lang.cancelar") }}</button>


                    </div>
                </div>
            </div><!-- panel-footer -->
        </div>
    </div>
</div>


<script>


    $('#btnChangeCenter, #btnChangeCenterGral').click(function (event) {

        event.preventDefault(); // To prevent following the link (optional)

        var url = "{{ url('centers/list_centers') }}";
        $("#changecenter_form").load(url);
        $("#modalCenters").modal('show');

    });

    $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
        if (jqxhr.status == 401 || jqxhr.status == 419){
            alert('{{ trans('general/admin_lang.session_expired') }}');
            window.location.reload();
        }
    });


</script>
