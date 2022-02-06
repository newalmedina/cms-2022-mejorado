@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')
    <!-- DataTables -->
    <link href="{{ asset('/assets/admin/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/assets/admin/vendor/datatables.net/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')
    @include('admin.includes.modals')
    @include('admin.includes.errors')
    @include('admin.includes.success')

    <!-- Modal Modulos a generar-->
    <div class="modal fade" id="generateModuleModal" tabindex="-1" role="dialog" aria-labelledby="generateModuleModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">{!! __('crud-generator::modules/admin_lang.select_actions') !!}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formModuleGenerate" action="#">

                        {!! Form::hidden('module_id', '', ['id' => 'module_id']) !!}

                        <form role="form" action="/" id="generateModuleModal-form">


                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('checkall', 1, true, ['id' => 'checkall', 'class' => 'form-check-input']) !!}
                                    <label for="checkall" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_checkall') }}
                                    </label>
                                </div>
                            </div>

                            <hr>
                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('clean_all', 1, true, ['id' => 'clean_all', 'class' => 'form-check-input']) !!}
                                    <label for="clean_all" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_clean_all') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('model', 1, true, ['id' => 'model', 'class' => 'form-check-input']) !!}
                                    <label for="model" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_model') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('views', 1, true, ['id' => 'views', 'class' => 'form-check-input']) !!}
                                    <label for="views" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_views') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('controller', 1, true, ['id' => 'controller', 'class' => 'form-check-input']) !!}
                                    <label for="controller" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_controller') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('requests', 1, true, ['id' => 'requests', 'class' => 'form-check-input']) !!}
                                    <label for="requests" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_requests') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('resources', 1, true, ['id' => 'resources', 'class' => 'form-check-input']) !!}
                                    <label for="resources" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_resources') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('test', 1, true, ['id' => 'test', 'class' => 'form-check-input']) !!}
                                    <label for="test" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_test') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('api', 1, true, ['id' => 'api', 'class' => 'form-check-input']) !!}
                                    <label for="api" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_api') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('menu', 1, true, ['id' => 'menu', 'class' => 'form-check-input']) !!}
                                    <label for="menu" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_menu') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('routes', 1, true, ['id' => 'routes', 'class' => 'form-check-input']) !!}
                                    <label for="routes" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_routes') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('translations', 1, true, ['id' => 'translations', 'class' => 'form-check-input']) !!}
                                    <label for="translations" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_translations') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('database', 1, true, ['id' => 'database', 'class' => 'form-check-input']) !!}
                                    <label for="database" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_database') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('seeds', 1, true, ['id' => 'seeds', 'class' => 'form-check-input']) !!}
                                    <label for="seeds" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_seeds') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    {!! Form::checkbox('post', 1, true, ['id' => 'post', 'class' => 'form-check-input']) !!}
                                    <label for="post" class="form-check-label">
                                        {{ trans('crud-generator::modules/admin_lang.generate_post') }}
                                    </label>
                                </div>
                            </div>

                            {!! Form::close() !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left"
                        data-dismiss="modal">{{ trans('general/admin_lang.close') }}</button>
                    <button type="submit" class="btn btn-primary"
                        id="generateModule">{{ trans('crud-generator::modules/admin_lang.generar') }}</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Generando-->
    <div class="modal fade" id="generateModal" tabindex="-1" role="dialog" aria-labelledby="generateModal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="loader"></div>
                    <div clas="loader-txt">
                        <p>{!! trans('crud-generator::modules/admin_lang.generate_module') !!}<br><br><small>{!! trans('crud-generator::modules/admin_lang.paciencia') !!}</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Limpiando-->
    <div class="modal fade" id="cleanModal" tabindex="-1" role="dialog" aria-labelledby="cleanModal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="loader"></div>
                    <div clas="loader-txt">
                        <p>{!! trans('crud-generator::modules/admin_lang.clean_module') !!}<br><br><small>{!! trans('crud-generator::modules/admin_lang.paciencia') !!}</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Importar -->
    <div class="modal" id="modal_import" tabindex="-1" role="dialog" aria-labelledby="importModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('crud-generator::modules/admin_lang.importar_modulo_header') }}</h4>
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="import_modulo_form">
                        <div class="input-group">
                            <input type="text" class="form-control" id="nombrefichero" readonly>
                            <span class="input-group-btn">
                                <div class="btn btn-primary btn-file">
                                    {{ trans('crud-generator::modules/admin_lang.search_file') }}
                                    {!! Form::file('import_modulo',array('id'=>'import_modulo', 'multiple'=>false)) !!}
                                </div>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary float-left" data-dismiss="modal">
                        {{ trans("crud-generator::modules/admin_lang.cerrar") }}
                    </button>
                    <button id="import_submit" type="button" class="btn btn-success">
                        {{ trans('crud-generator::modules/admin_lang.importar_modulo') }}
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Default card -->
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header with-border">
                    <h3 class="card-title">{{ trans('crud-generator::modules/admin_lang.list') }}</h3>
                </div>

                <div class="card-body">
                    @if (Auth::user()->isAbleTo('admin-modulos-crud-create'))
                        <a href="{{ url('admin/crud-generator/create') }}" class="btn btn-success float-right"><i
                                class="fa fa-plus-circle" aria-hidden="true"></i>
                            {{ trans('crud-generator::modules/admin_lang.new') }}</a>
                    @endif

                    @if (Auth::user()->isAbleTo('admin-modulos-crud-create'))
                        <a id="importar_modulo" href="{{ url('admin/crud-generator/import') }}" class="btn bg-purple float-right mr-5">
                            <i class="fa fa-upload" aria-hidden="true"></i> {{ trans('crud-generator::modules/admin_lang.import_module') }}
                        </a>
                    @endif
                </div>

                <!-- /.card-header -->
                <div class="card-body">
                    <table id="table_crud_generator" class="table table-bordered table-striped" aria-hidden="true">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>

@endsection

@section('foot_page')
    <!-- DataTables -->
    <script src="{{ asset('/assets/admin/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/admin/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/assets/admin/vendor/datatables.net/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/assets/admin/vendor/datatables.net/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- page script -->
    <script type="text/javascript">
        var oTable = '';
        var selected = [];

        $(function() {
            oTable = $('#table_crud_generator').DataTable({
                "stateSave": true,
                "stateDuration": 60,
                "processing": true,
                "responsive": true,
                "serverSide": true,
                ajax: {
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    url: "{{ url('admin/crud-generator/list') }}",
                    type: "POST"
                },
                order: [
                    [3, "asc"]
                ],
                columns: [{
                        "title": "{!! trans('crud-generator::modules/admin_lang.active') !!}",
                        orderable: false,
                        searchable: false,
                        data: 'active',
                        sWidth: '50px'
                    },
                    {
                        "title": "{!! trans('crud-generator::modules/admin_lang.type') !!}",
                        orderable: true,
                        searchable: true,
                        data: 'type',
                        name: 'crud_types.name',
                        sWidth: ''
                    },
                    {
                        "title": "{!! trans('crud-generator::modules/admin_lang.theme') !!}",
                        orderable: true,
                        searchable: true,
                        data: 'theme',
                        name: 'crud_themes.name',
                        sWidth: ''
                    },
                    {
                        "title": "{!! trans('crud-generator::modules/admin_lang.name') !!}",
                        orderable: true,
                        searchable: true,
                        data: 'title',
                        name: 'title',
                        sWidth: ''
                    },
                    {
                        "title": "{!! trans('crud-generator::modules/admin_lang.model') !!}",
                        orderable: true,
                        searchable: true,
                        data: 'model',
                        name: 'model',
                        sWidth: ''
                    },
                    {
                        "title": "{!! trans('crud-generator::modules/admin_lang.actions') !!}",
                        orderable: false,
                        searchable: false,
                        sWidth: '220px',
                        data: 'actions'
                    }

                ],
                "fnDrawCallback": function(oSettings) {
                    $('[data-toggle="popover"]').mouseover(function() {
                        $(this).popover("show");
                    });

                    $('[data-toggle="popover"]').mouseout(function() {
                        $(this).popover("hide");
                    });
                },
                oLanguage: {!! json_encode(trans('datatable/lang')) !!}

            });

            var state = oTable.state.loaded();
            $('tfoot th', $('#table_crud_generator')).each(function(colIdx) {
                var title = $('tfoot th', $('#table_crud_generator')).eq($(this).index()).text();
                if (oTable.settings()[0]['aoColumns'][$(this).index()]['bSearchable']) {
                    var defecto = "";
                    if (state) defecto = state.columns[colIdx].search.search;

                    $(this).html(
                        '<input type="text" class="form-control input-small input-inline" placeholder="' +
                        oTable.context[0].aoColumns[colIdx].title + ' ' + title + '" value="' +
                        defecto + '" />');
                }
            });

            $('#table_crud_generator').on('keyup change', 'tfoot input', function(e) {
                oTable
                    .column($(this).parent().index() + ':visible')
                    .search(this.value)
                    .draw();
            });

        });

        function changeStatus(url) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    if (data) {
                        oTable.ajax.reload(null, false);
                    } else {
                        $("#modal_alert").addClass('modal-danger');
                        $("#alertModalBody").html(
                            "<i class='fa fa-bug text-danger' style='font-size: 64px; float: left; margin-right:15px;'></i> {{ trans('general/admin_lang.errorajax') }}"
                            );
                        $("#modal_alert").modal('toggle');
                    }
                }
            });
        }

        function deleteElement(url) {
            var strBtn = "";

            $("#confirmModalLabel").html("{{ trans('general/admin_lang.warning_title') }}");
            $("#confirmModalBody").html("{{ trans('general/admin_lang.delete_question') }}");
            strBtn +=
                '<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general/admin_lang.close') }}</button>';
            strBtn += '<button type="button" class="btn btn-primary" onclick="javascript:deleteinfo(\'' + url +
                '\');">{{ trans('general/admin_lang.borrar_item') }}</button>';
            $("#confirmModalFooter").html(strBtn);
            $('#modal_confirm').modal('toggle');
        }

        function deleteinfo(url) {
            $.ajax({
                url: url,
                type: 'POST',
                "headers": {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                data: {
                    _method: 'delete'
                },
                success: function(data) {
                    $('#modal_confirm').modal('hide');
                    if (data) {
                        $("#modal_alert").addClass('modal-success');
                        $("#alertModalHeader").html(
                            '{{ trans('crud-generator::modules/admin_lang.module_delete') }}');
                        $("#alertModalBody").html(
                            "<i class='fa fa-check-circle text-success' style='font-size: 64px; float: left; margin-right:15px;'></i> " +
                            data.msg);
                        $("#modal_alert").modal('toggle');
                        oTable.ajax.reload(null, false);
                    } else {
                        $("#modal_alert").addClass('modal-danger');
                        $("#alertModalBody").html(
                            "<i class='fa fa-bug text-danger' style='font-size: 64px; float: left; margin-right:15px;'></i> {{ trans('general/admin_lang.errorajax') }}"
                            );
                        $("#modal_alert").modal('toggle');
                    }
                    return false;
                }

            });
            return false;
        }

        function doGenerate(id) {
            $('#module_id').val(id);

            $("#generateModuleModal").modal({
                backdrop: "static", //remove ability to close modal with click
                keyboard: false, //remove option to close with keyboard
                show: true //Display loader!
            });
        }

        $('#checkall').on('change', function(e) {
            $('.form-check-input').prop('checked', $(this).prop("checked"));
        });

        $('.form-check-input').change(function() {
            if ($('.form-check-input:checked').length == $('.form-check-input').length) {
                $('#checkall').prop('checked', true);
            } else {
                $('#checkall').prop('checked', false);
            }
        });


        $('#generateModule').on('click', function(e) {
            e.preventDefault();
            $("#generateModal").modal({
                backdrop: "static", //remove ability to close modal with click
                keyboard: false, //remove option to close with keyboard
                show: true //Display loader!
            });
            $('#generateModuleModal').modal('hide');


            var dataString = $('#formModuleGenerate').serialize();

            $.ajax({
                url: "{{ url('admin/crud-generator/generate') }}",
                type: "POST",
                data: dataString,
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#generateModal').modal('hide');
                    if (data) {
                        if (data.status === 'ok') {
                            $("#modal_alert").addClass('modal-success');
                            $("#alertModalHeader").html(
                                '{{ trans('crud-generator::modules/admin_lang.titulo_generate') }}'
                                );
                            $("#alertModalBody").html(
                                "<i class='fa fa-check-circle text-success' style='font-size: 64px; float: left; margin-right:15px;'></i> " +
                                data.msg);
                            $("#modal_alert").modal('toggle');
                        } else {
                            $("#modal_alert").addClass('modal-warning');
                            $("#alertModalBody").html(
                                "<i class='fa fa-warning text-warning' style='font-size: 64px; float: left; margin-right:15px;'></i> " +
                                data.msg);
                            $("#modal_alert").modal('toggle');
                        }
                    } else {
                        $("#modal_alert").addClass('modal-danger');
                        $("#alertModalBody").html(
                            "<i class='fa fa-bug text-danger' style='font-size: 64px; float: left; margin-right:15px;'></i> {{ trans('crud-generator::modules/admin_lang.errorajax') }}"
                            );
                        $("#modal_alert").modal('toggle');
                    }
                    return false;

                }
            });
            return false;

        });



        function doClean(id) {
            var strBtn = "";

            $("#confirmModalLabel").html("{{ trans('general/admin_lang.warning_title') }}");
            $("#confirmModalBody").html("{{ trans('crud-generator::modules/admin_lang.seguro_limpiar') }}");
            strBtn +=
                '<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general/admin_lang.close') }}</button>';
            strBtn += '<button type="button" class="btn btn-primary" onclick="javascript:goClean(' + id +
                ');">{{ trans('crud-generator::modules/admin_lang.clean') }}</button>';
            $("#confirmModalFooter").html(strBtn);
            $('#modal_confirm').modal('toggle');
        }

        function goClean(id) {
            $("#cleanModal").modal({
                backdrop: "static", //remove ability to close modal with click
                keyboard: false, //remove option to close with keyboard
                show: true //Display loader!
            });
            $('#modal_confirm').modal('hide');

            $.ajax({
                url: "{{ url('admin/crud-generator/clean/') }}/" + id,
                type: "GET",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#cleanModal').modal('hide');
                    if (data) {
                        if (data.status === 'ok') {
                            $("#modal_alert").addClass('modal-success');
                            $("#alertModalHeader").html(
                                '{{ trans('crud-generator::modules/admin_lang.titulo_clean') }}');
                            $("#alertModalBody").html(
                                "<i class='fa fa-check-circle text-success' style='font-size: 64px; float: left; margin-right:15px;'></i> " +
                                data.msg);
                            $("#modal_alert").modal('toggle');
                        } else {
                            $("#modal_alert").addClass('modal-warning');
                            $("#alertModalBody").html(
                                "<i class='fa fa-warning text-warning' style='font-size: 64px; float: left; margin-right:15px;'></i> " +
                                data.msg);
                            $("#modal_alert").modal('toggle');
                        }
                    } else {
                        $("#modal_alert").addClass('modal-danger');
                        $("#alertModalBody").html(
                            "<i class='fa fa-bug text-danger' style='font-size: 64px; float: left; margin-right:15px;'></i> {{ trans('crud-generator::modules/admin_lang.errorajax') }}"
                            );
                        $("#modal_alert").modal('toggle');
                    }
                    return false;

                }
            });
            return false;
        }

        function doExport(id) {
            var strBtn = "";

            $("#confirmModalLabel").html("{{ trans('crud-generator::modules/admin_lang.exportar_modulo') }}");
            $("#confirmModalBody").html("{{ trans('crud-generator::modules/admin_lang.exportar_modulo_question') }}");
            strBtn += '<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general/admin_lang.close') }}</button>';
            strBtn += '<button id="modal_submit_button" type="button" class="btn btn-primary" onclick="javascript:exportarmoduloinfo(\'' + id + '\');">{{ trans('crud-generator::modules/admin_lang.exportar') }}</button>';
            $("#confirmModalFooter").html(strBtn);
            $('#modal_confirm').modal('toggle');
        }

        function exportarmoduloinfo(id) {

            var url = `/admin/crud-generator/${id}/export`;
            $('#modal_confirm').modal('hide');
            location.href = url;

        }

        $("#importar_modulo").click(function (e) {
            e.preventDefault();


            $("#modal_import").modal({
                backdrop: "static", //remove ability to close modal with click
                keyboard: false, //remove option to close with keyboard
                show: true //Display loader!
            });

            $("#import_modulo").change(function () {
                $("#nombrefichero").val($(this)[0].files[0].name);
            });


            $("#import_submit").click(function () {

                var btnEnviar = $("#import_submit");
                var file = $("#import_modulo")[0].files[0];
                if (/^zip?$/.test(file.name.split('.').pop())) {
                    btnEnviar.addClass('disabled');
                    btnEnviar.prepend('<i class="fa fa-spinner fa-spin" aria-hidden="true">&nbsp;</i>');


                    var formData = new FormData();
                    formData.append("plantilla", file);
                    formData.append("_token", "{{ csrf_token() }}");

                    $("#import_submit").attr("disabled", true);

                    $.ajax({
                        url: "{{ url("admin/crud-generator/import") }}",
                        type: 'POST',
                        contentType: false,
                        data: formData,
                        processData: false,
                        cache: false
                    }).done(function (data) {
                        var btnEnviar = $("#import_submit");
                        btnEnviar.removeClass('disabled');
                        btnEnviar.attr("disabled", false);
                        btnEnviar.find('i').remove();
                        if(data.result) {

                            $("#modal_import").modal("hide");
                            $("#import_submit").attr("disabled", false);
                            oTable.ajax.reload();

                        } else {
                            alert("{{ trans("crud-generator::modules/admin_lang.error_import") }}");
                        }
                    });
                } else {
                    alert("{{ trans("crud-generator::modules/admin_lang.not_valid") }}");
                }
            });

        });
    </script>

@stop
