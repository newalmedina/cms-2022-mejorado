@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')

    <!-- DataTables -->
    <link href="{{ asset('/assets/admin/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/admin/vendor/datatables.net/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')
    @include('admin.includes.modals')
    @include('admin.includes.errors')
    @include('admin.includes.success')


    <!-- Default box -->
    <div class="row">
        <div class="col-12">
            <div class="card card-secondary card-outline">
                <div class="card-header with-border">
                    <h3 class="card-title">{{ trans('Idiomas::idiomas/admin_lang.listado') }}</h3>
                </div>

                <div class="card-body row">
                    <div class="col-8" style="padding-left:0">
                        <div class="input-group">
                            <select id="massive-operations" name="massive-operations" class="form-control select2">
                                <option value="">{{ trans('general/admin_lang.selecciona_operacion_múltiples') }}</option>
                                <option value="delete_selected">{{ trans('general/admin_lang.borrar_seleccionados') }}
                                </option>
                            </select>

                            <span class="input-group-btn mr-3">
                                <button id="btn-massive-operations" name="btn-massive-operations" type="button"
                                    class="btn btn-info">
                                    {{ trans('general/admin_lang.ejecutar') }}
                                </button>
                            </span>
                        </div>

                    </div>
                    <div class="col-4">
                        @if (Auth::user()->isAbleTo('admin-idiomas-create'))
                            <a href="{{ url('admin/idiomas/create') }}" class="btn btn-success float-right">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                {{ trans('Idiomas::idiomas/admin_lang.nueva') }}
                            </a>
                        @endif
                    </div>
                </div>

                <!-- /.box-header -->
                <div class="card-body">
                    <table id="table_idiomas" class="table table-bordered table-striped" aria-hidden="true">
                        <thead>
                            <tr>
                                <th scope="col">
                                <th scope="col">
                                <th scope="col">
                                <th scope="col">
                                <th scope="col">
                                <th scope="col">
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col">
                                <th scope="col">
                                <th scope="col">
                                <th scope="col">
                                <th scope="col">
                                <th scope="col">
                            </tr>
                        </tfoot>
                    </table>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
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
            oTable = $('#table_idiomas').DataTable({
                "stateSave": true,
                "stateDuration": 60,
                "processing": true,
                "responsive": true,
                "serverSide": true,
                "pageLength": 50,
                ajax: {
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    url: "{{ url('admin/idiomas/list') }}",
                    type: "POST"
                },
                order: [
                    [2, "asc"]
                ],
                columns: [{
                        "title": '<input type="checkbox" name="chk_all" id="chk_all" value="0" onclick="javascript:select_all();">',
                        orderable: false,
                        searchable: false,
                        data: "check",
                        sWidth: '30px'
                    },

                    {
                        "title": "{!! trans('Idiomas::idiomas/admin_lang.fields.active') !!}",
                        orderable: false,
                        searchable: false,
                        data: 'active',
                        sWidth: '50px'
                    },
                    {
                        "title": "{!! trans('Idiomas::idiomas/admin_lang.fields.default') !!}",
                        orderable: false,
                        searchable: false,
                        data: 'default',
                        sWidth: '50px'
                    },
                    {
                        "title": "{!! trans('Idiomas::idiomas/admin_lang.fields.code') !!}",
                        orderable: true,
                        searchable: true,
                        data: 'code',
                        name: 'c.code',
                        sWidth: ''
                    },
                    {
                        "title": "{!! trans('Idiomas::idiomas/admin_lang.fields.name') !!}",
                        orderable: true,
                        searchable: true,
                        data: 'name',
                        name: 'ct.name',
                        sWidth: ''
                    },
                    {
                        "title": "{!! trans('Idiomas::idiomas/admin_lang.acciones') !!}",
                        orderable: false,
                        searchable: false,
                        sWidth: '110px',
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
            $('tfoot th', $('#table_idiomas')).each(function(colIdx) {
                var title = $('tfoot th', $('#table_idiomas')).eq($(this).index()).text();
                if (oTable.settings()[0]['aoColumns'][$(this).index()]['bSearchable']) {
                    var defecto = "";
                    if (state) defecto = state.columns[colIdx].search.search;

                    $(this).html(
                        '<input type="text" class="form-control input-small input-inline" placeholder="' +
                        oTable.context[0].aoColumns[colIdx].title + ' ' + title + '" value="' +
                        defecto + '" />');
                }
            });

            $('#table_idiomas').on('keyup change', 'tfoot input', function(e) {
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
                            "<i class='fa fa-bug' style='font-size: 64px; float: left; margin-right:15px;'></i> {{ trans('general/admin_lang.errorajax') }}"
                            );
                        $("#modal_alert").modal('toggle');
                    }
                }
            });
        }

        function setDefault(url) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    if (data) {
                        oTable.ajax.reload(null, false);
                    } else {
                        $("#modal_alert").addClass('modal-danger');
                        $("#alertModalBody").html(
                            "<i class='fa fa-bug' style='font-size: 64px; float: left; margin-right:15px;'></i> {{ trans('general/admin_lang.errorajax') }}"
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
                        $("#alertModalHeader").html("Borrado de posts");
                        $("#alertModalBody").html(
                            "<i class='fa fa-check-circle' style='font-size: 64px; float: left; margin-right:15px;'></i> " +
                            data.msg);
                        $("#modal_alert").modal('toggle');
                        oTable.ajax.reload(null, false);
                    } else {
                        $("#modal_alert").addClass('modal-danger');
                        $("#alertModalBody").html(
                            "<i class='fa fa-bug' style='font-size: 64px; float: left; margin-right:15px;'></i> {{ trans('general/admin_lang.errorajax') }}"
                            );
                        $("#modal_alert").modal('toggle');
                    }
                    return false;
                }

            });
            return false;
        }

        function select_all() {
            $('input:checkbox').not(this).prop('checked', $("#chk_all").is(':checked'));
        }

        // Handle click on checkbox to set state of "Select all" control
        $('#table_idiomas tbody').on('change', 'input[type="checkbox"]', function() {
            // If checkbox is not checked
            if (!this.checked) {
                var el = $('#chk_all').get(0);
                // If "Select all" control is checked and has 'indeterminate' property
                if (el && el.checked && ('indeterminate' in el)) {
                    // Set visual state of "Select all" control
                    // as 'indeterminate'
                    el.indeterminate = true;
                }
            }
        });

        $('#btn-massive-operations').on('click', function() {
            var operation = $("#massive-operations").val();
            if (operation === 'delete_selected') {
                deleteSelected();
            }
        });

        function deleteSelected() {
            var ids = getSelectedRecords();
            if (ids.length > 0) {
                deleteElements('{{ url('admin/idiomas/delete-selected') }}', ids);
            }
        }

        function getSelectedRecords() {
            var ids = [];
            $('#table_idiomas input[type="checkbox"]').each(function() {
                // If checkbox is checked
                if (this.checked) {
                    ids.push(this.value);
                }
            });

            return ids;
        }

        function deleteElements(url, ids) {
            var strBtn = "";

            $("#confirmModalLabel").html("{{ trans('general/admin_lang.warning_title') }}");
            $("#confirmModalBody").html("{{ trans('general/admin_lang.delete_question_selected') }}");
            strBtn +=
                '<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general/admin_lang.close') }}</button>';
            strBtn += '<button type="button" class="btn btn-primary" onclick="javascript:deleteElementsConfirm(\'' + url +
                '\',\'' + ids + '\');">{{ trans('general/admin_lang.borrar_item') }}</button>';
            $("#confirmModalFooter").html(strBtn);
            $('#modal_confirm').modal('toggle');
        }

        function deleteElementsConfirm(url, ids) {
            $.ajax({
                url: url,
                type: 'POST',
                "headers": {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                data: {
                    ids: ids
                },
                success: function(data) {
                    $('#modal_confirm').modal('hide');
                    if (data) {
                        $("#modal_alert").addClass('modal-success');
                        $("#alertModalHeader").html("Borrado de registros");
                        $("#alertModalBody").html(
                            "<i class='fa fa-check-circle' style='font-size: 64px; float: left; margin-right:15px;'></i> " +
                            data.msg);
                        $("#modal_alert").modal('toggle');

                        $("#chk_all").prop("checked", false);

                        oTable.ajax.reload(null, false);
                    } else {
                        $("#modal_alert").addClass('modal-danger');
                        $("#alertModalBody").html(
                            "<i class='fa fa-bug' style='font-size: 64px; float: left; margin-right:15px;'></i> {{ trans('general/admin_lang.errorajax') }}"
                            );
                        $("#modal_alert").modal('toggle');
                    }
                    return false;
                }

            });
            return false;
        }
    </script>

@stop
