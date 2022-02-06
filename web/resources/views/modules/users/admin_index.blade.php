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
    <div>
        @include('admin.includes.modals')

        <div class="row">
            <x-info-box id="totalUsuarios" color="bg-info" value="0" icon="fas fa-user">
                Total de usuarios
            </x-info-box>
            <x-info-box id="nuevosUsuarios" color="bg-success" value="0" icon="fas fa-users">
                Nuevos usuarios últimos 30 días
            </x-info-box>
            <x-info-box id="activosUsuarios" color="bg-warning" value="0" icon="fas fa-user-plus">
                Usuarios activos en la última hora
            </x-info-box>


        </div>


        <!-- Default box -->
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">{{ trans('users/lang.listado_usuarios') }}</h3>
                    </div>

                    <div class="card-body">
                        @if (Auth::user()->isAbleTo('admin-users-create'))
                            <a href="{{ url('admin/users/create') }}" class="btn btn-success float-right"><i
                                    class="fa fa-plus-circle" aria-hidden="true"></i>
                                {{ trans('users/lang.nueva_usuario') }}</a>
                        @endif
                    </div>

                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="table_users" class="table table-bordered table-striped" aria-hidden="true">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
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
                                    <th scope="col"></th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ trans('users/lang.export') }}</h3>
                    </div>

                    <div class="card-body">

                        <a href="{{ url('admin/users/generateExcel') }}" class="btn btn-app">
                            <i class="far fa-file-excel"></i>
                            {{ trans('users/lang.exportar_usuarios') }}
                        </a>

                    </div>

                </div>

            </div>
        </div>
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

        $(function() {
            getStatsData();


            oTable = $('#table_users').DataTable({
                "stateSave": true,
                "stateDuration": 60,
                "processing": true,
                "responsive": true,
                "serverSide": true,
                "pageLength": 100,
                ajax: {
                    "headers": {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    url: "{{ url('admin/users/list') }}",
                    type: "POST"
                },
                order: [
                    [2, "asc"]
                ],
                columns: [{
                        orderable: false,
                        searchable: false,
                        width: '20px',
                        data: 'active',
                    },
                    {
                        "title": "{!! trans('users/lang.online') !!}",
                        orderable: false,
                        searchable: false,
                        width: '20px',
                        data: 'online',
                        name: 'online'
                    },
                    {
                        "title": "{!! trans('users/lang.nombre_usuario') !!}",
                        orderable: true,
                        searchable: true,
                        data: 'first_name',
                        name: 'user_profiles.first_name',
                        width: ''
                    },
                    {
                        "title": "{!! trans('users/lang._APELLIDOS_USUARIO') !!}",
                        orderable: true,
                        searchable: true,
                        data: 'last_name',
                        name: 'user_profiles.last_name',
                        width: ''
                    },
                    {
                        "title": "{!! trans('users/lang.email_usuario') !!}",
                        orderable: true,
                        searchable: true,
                        data: 'email',
                        name: 'users.email',
                        width: '200px'
                    },
                    {
                        "title": "{!! trans('users/lang.username') !!}",
                        orderable: true,
                        searchable: true,
                        data: 'username',
                        name: 'users.username',
                        width: '200px'
                    },
                    {
                        "title": "{!! trans('users/lang.acciones') !!}",
                        orderable: false,
                        searchable: false,
                        width: '130px',
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
            $('tfoot th', $('#table_users')).each(function(colIdx) {
                var title = $('tfoot th', $('#table_users')).eq($(this).index()).text();
                if (oTable.settings()[0]['aoColumns'][$(this).index()]['bSearchable']) {
                    var defecto = "";
                    if (state) defecto = state.columns[colIdx].search.search;

                    $(this).html(
                        '<input type="text" class="form-control input-small input-inline" placeholder="' +
                        oTable.context[0].aoColumns[colIdx].title + ' ' + title + '" value="' +
                        defecto + '" />');
                }
            });

            $('#table_users').on('keyup change', 'tfoot input', function(e) {
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

        function deleteElement(url) {
            var strBtn = "";

            $("#confirmModalLabel").html("{{ trans('users/lang.user_warning_title') }}");
            $("#confirmModalBody").html("{{ trans('users/lang.user_delete_question') }}");
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
                        if (data.success) {
                            $("#modal_alert").addClass('modal-success');
                            $("#alertModalHeader").html("Borrado de usuario");
                            $("#alertModalBody").html(
                                "<i class='fa fa-check-circle' style='font-size: 64px; float: left; margin-right:15px;'></i> " +
                                data.msg);
                            $("#modal_alert").modal('toggle');
                            oTable.ajax.reload(null, false);
                        } else {
                            $("#modal_alert").addClass('modal-warning');
                            $("#alertModalBody").html(
                                "<i class='fa fa-warning' style='font-size: 64px; float: left; margin-right:15px;'></i> " +
                                data.msg);
                            $("#modal_alert").modal('toggle');
                        }
                    } else {
                        $("#modal_alert").addClass('modal-danger');
                        $("#alertModalBody").html(
                            "<i class='fa fa-bug' style='font-size: 64px; float: left; margin-right:15px;'></i> {{ trans('users/lang.errorajax') }}"
                        );
                        $("#modal_alert").modal('toggle');
                    }
                    return false;
                }
            });
            return false;
        }

        function suplantarElement(url) {
            var strBtn = "";

            $("#confirmModalLabel").html("{{ trans('users/lang.user_warning_title') }}");
            $("#confirmModalBody").html("{{ trans('users/lang.user_suplantar_question') }}");
            strBtn +=
                '<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general/admin_lang.close') }}</button>';
            strBtn += '<a class="btn btn-primary" href="' + url + '">{{ trans('users/lang.suplantar_item') }}</a>';

            $("#confirmModalFooter").html(strBtn);
            $('#modal_confirm').modal('toggle');
        }


        function getStatsData() {

            axios.get('/admin/users/userStats').then(res => {

                    let results = res.data;

                    $('#totalUsuarios').html(results.total);
                    $('#nuevosUsuarios').html(results.nuevos);
                    $('#activosUsuarios').html(results.activos);

                })
                .catch(function(error) {
                    // handle error
                    console.log(error);
                })
                .then(function() {
                    // always executed
                });

        }
    </script>
@stop
