@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')
    <style>
        .ui-sortable tr {
            cursor: pointer;
        }

        .ui-sortable tr:hover {
            background: rgba(244, 251, 17, 0.45);
        }

        #spinner {
            background: rgba(0, 0, 0, 0.1);
            position: absolute;
            width: 100%;
            padding: 50px;
            text-align: center;
            display: none;
        }

    </style>
    <link href="{{ asset("assets/admin/vendor/jquery-ui/jquery-ui.min.css") }}" rel="stylesheet" type="text/css" />
@stop

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('admin/crud-generator') }}">{{ trans('crud-generator::modules/admin_lang.list') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('admin/crud-generator/' . $module->id . '/fields') }}">{{ trans('crud-generator::fields/admin_lang.title') }}</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')

    @include('admin.includes.errors')
    @include('admin.includes.success')

    {!! Form::open($form_data, ['role' => 'form']) !!}

    <section class="content">

        <div class="row">
            <div class="col-12">
                <div class="card card-secondary card-outline">
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table  table-striped table-hover" id="ordered_list">
                            <thead>
                                <tr>
                                    <th style="width:30px;"></th>
                                    <th>{!! trans('crud-generator::fields/admin_lang.order') !!}</th>
                                    <th>{!! trans('crud-generator::fields/admin_lang.name') !!}</th>
                                    <th>{!! trans('crud-generator::fields/admin_lang.visual') !!}</th>
                                    <th>{!! trans('crud-generator::fields/admin_lang.type') !!}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($fields as $field)
                                    <tr>
                                        <td>
                                            {!! Form::hidden('row_id[]', $field->id, array('id' => 'row_id[]')) !!}
                                            <i class="fas fa-arrows-alt text-info icon-move" aria-hidden="true"></i>
                                        </td>
                                        <td class='priority'>{{ $loop->index + 1 }}</td>
                                        <td>{{ $field->column_name }}</td>
                                        <td>{{ $field->column_title }}</td>
                                        <td><span class="badge badge-success">{{ $field->name }}</span></td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <div class="card card-solid">

                    <div class="card-footer">

                        <a href="{{ url('/admin/crud-generator/'.$module->id.'/fields') }}" class="btn btn-default">{{ trans('general/admin_lang.cancelar') }}</a>
                        <button type="submit" class="btn btn-info float-right text-light">{{ trans('general/admin_lang.save') }}</button>

                    </div>

                </div>
            </div>
        </div>


    </section>
    {!! Form::close() !!}

@endsection

@section('foot_page')
    <script type="text/javascript" src="{{ asset("assets/admin/vendor/jquery-ui/jquery-ui.min.js") }}"></script>

    <script type="text/javascript">
        $(function() {
            //Make table sortable
            $("#ordered_list tbody").sortable({
                stop: function(event, ui) {
                    renumber_table('#ordered_list')
                }
            }).disableSelection();

        });


        //Renumber table rows
        function renumber_table(tableID) {
            $(tableID + " tr").each(function() {
                count = $(this).parent().children().index($(this)) + 1;
                $(this).find('.priority').html(count);
            });
        }

    </script>
@stop
