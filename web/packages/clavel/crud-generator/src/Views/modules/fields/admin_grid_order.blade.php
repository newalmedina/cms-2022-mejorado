@extends('admin.layouts.default')
{{-- http://jsfiddle.net/vacidesign/uskx816g/ --}}
@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')
    <link href="{{ asset("assets/admin/vendor/jquery-ui/jquery-ui.min.css") }}" rel="stylesheet" type="text/css" />
    <style>
        .block {
            background: #f2f2f2;
            position: relative;
            padding: 15px;
            border: 1px solid #ccc;

            &:not(:first-child) {
                margin-top: 10px;
            }
        }

        .modifier {
            float: right;
            margin-left: 8px;
            font-size: 14px;
        }

        .action {
            color: #1abc9c;
        }

        .edit {
            color: #888888;
        }

        .column-selector {
            position: relative;
            padding-bottom: 5px;
            display: inline-block;
        }

        .row-actions {
            position: relative;
            padding-bottom: 5px;
            display: inline-block;
        }

        .remove {
            color: #FF0000;
        }

        .dropdown-menu {
            top: inherit;
        }

        .blocks {
            margin-bottom: 0;
        }

        .panel {
            border-radius: 0;
            margin-bottom: 20px;
            background-color: #fff;
            border: 1px solid transparent;
            border-radius: 4px;
            -webkit-box-shadow: 0 1px 1px rgb(0 0 0 / 5%);
            box-shadow: 0 1px 1px rgb(0 0 0 / 5%);
        }

        .panel-container {
            background-color: #f2f2f2;
        }

        .panel-default {
            border-color: #ddd;
        }

        .panel-body {
            padding: 15px;
        }

        .row>.panel {
            background-color: #f2f2f2;
            width: 100%;
        }

        .builder {
            padding: 0;
        }

        .block-placeholder {
            background: #DADADA;
            position: relative;
        }

        .block-placeholder:after {
            content: " ";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 15px;
            background-color: #FFF;
        }

    </style>
@stop

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('admin/crud-generator') }}">{{ trans('crud-generator::modules/admin_lang.list') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('admin/crud-generator/' . $module->id . '/fields') }}">{{ trans('crud-generator::fields/admin_lang.title') }}</a> </li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')
    @include('admin.includes.errors')
    @include('admin.includes.success')

    {!! Form::open($form_data, ['role' => 'form']) !!}
    <section class="content">
        <div class="row">
            <div class="col-12">

                <div class="card card-primary card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">{{ trans('crud-generator::fields/admin_lang.grid_no_ordered') }}</h3>
                    </div>
                    <div class="card-body">
                        <div id="wrapper" class="builder">

                            <div class="container">
                                <div class="row">
                                    <div class="panel panel-default panel-body panel-container sortable">
                                        <div class="column-container">
                                            <div id="0" class="col-12 column sortable">
                                                <div class="blocks panel panel-default panel-body">
                                                    @foreach ($fields as $field)
                                                        <div id="{{ $field->id }}" class="block clearfix">
                                                            {!! Form::hidden('item['.$field->id.'][id]', $field->id, ['id' => 'item['.$field->id.'][id]']) !!}
                                                            {!! Form::hidden('item['.$field->id.'][row]',0, ['id' => 'item['.$field->id.'][row]']) !!}
                                                            {!! Form::hidden('item['.$field->id.'][column]',0, ['id' => 'item['.$field->id.'][column]']) !!}


                                                            <span class="handle ui-sortable-handle">
                                                                <i class="fas fa-arrows-alt text-info icon-move"></i>
                                                            </span>
                                                            <span class="text">{{ $field->column_title }}</span>
                                                            <small class="badge badge-warning">{{ $field->column_name }}</small>
                                                            <span class="badge badge-success">{{ $field->type->name }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(!empty($grid))
                <div class="card">
                    <div class="card-header  with-border">
                        <h3 class="card-title">{{ trans('crud-generator::fields/admin_lang.grid_ordered') }}</h3>
                        <div class="card-tools pull-right">
                            <button type="button" class="btn btn-success row-add"><i class="fa fa-plus-circle"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="wrapper" class="builder">

                            <div class="builder-body container">
                                @php
                                    $row = 0;
                                @endphp
                                @foreach ($grid as $item)
                                    {{-- Cerramos el anterior bloque si fuese necesario --}}
                                    @if($row > 0 && $item->row != $row)
                                        @include('crud-generator::fields.partials.admin_grid_end_row')
                                    @endif

                                    {{-- Abrimos bloque si fuese necesario --}}
                                    @if($item->row != $row)
                                        @include('crud-generator::fields.partials.admin_grid_start_row')
                                        @php
                                            $row = $item->row;
                                        @endphp
                                    @endif

                                    {{-- Ahora añadimos la columna --}}
                                    @include('crud-generator::fields.partials.admin_grid_column_row')


                                @endforeach

                                {{-- Cerramos el ultimo bloque --}}
                                @include('crud-generator::fields.partials.admin_grid_end_row')


                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- /.box -->

                <div class="card">

                    <div class="card-footer">

                        <a href="{{ url('/admin/crud-generator/' . $module->id . '/fields') }}"
                            class="btn btn-default">{{ trans('general/admin_lang.cancelar') }}</a>
                        <button type="submit"
                            class="btn btn-info float-right">{{ trans('general/admin_lang.save') }}</button>

                    </div>

                </div>
            </div>
        </div>
    </section>

    {!! Form::close() !!}
@endsection

@section('foot_page')
    <script type="text/javascript" src="{{ asset("assets/admin/vendor/jquery-ui/jquery-ui.min.js") }}"></script>

    <script>
        $("#wrapper .row").sortable({
            axis: "x",
            items: ".column"
        });

        $("#wrapper .container").sortable({
            axis: "y",
            items: ".row-data",
            placeholder: 'block-placeholder',
            revert: 150,
            start: function(e, ui) {

                placeholderHeight = ui.item.outerHeight();
                ui.placeholder.height(placeholderHeight + 15);
                $('<div class="block-placeholder-animator" data-height="' + placeholderHeight + '"></div>')
                    .insertAfter(ui.placeholder);

            },
            change: function(event, ui) {

                ui.placeholder.stop().height(0).animate({
                    height: ui.item.outerHeight() + 15
                }, 300);

                placeholderAnimatorHeight = parseInt($(".block-placeholder-animator").attr("data-height"));

                $(".block-placeholder-animator").stop().height(placeholderAnimatorHeight + 15).animate({
                    height: 0
                }, 300, function() {
                    $(this).remove();
                    placeholderHeight = ui.item.outerHeight();
                    $('<div class="block-placeholder-animator" data-height="' + placeholderHeight +
                        '"></div>').insertAfter(ui.placeholder);
                });

            },
            stop: function(e, ui) {

                $(".block-placeholder-animator").remove();

            },
        });

        // Block Controls
        function sortBlocks() {
            $("#wrapper .blocks").sortable({
                connectWith: '.blocks',
                placeholder: 'block-placeholder',
                revert: 150,
                start: function(e, ui) {
                    ui.item.width('240')
                    placeholderHeight = ui.item.outerHeight();
                    ui.placeholder.height(placeholderHeight + 15);
                    $('<div class="block-placeholder-animator" data-height="' + placeholderHeight + '"></div>')
                        .insertAfter(ui.placeholder);

                },
                change: function(event, ui) {
                    ui.placeholder.stop().height(0).animate({
                        height: ui.item.outerHeight() + 15
                    }, 300);

                    placeholderAnimatorHeight = parseInt($(".block-placeholder-animator").attr("data-height"));

                    $(".block-placeholder-animator").stop().height(placeholderAnimatorHeight + 15).animate({
                        height: 0
                    }, 300, function() {
                        $(this).remove();
                        placeholderHeight = ui.item.outerHeight();
                        $('<div class="block-placeholder-animator" data-height="' + placeholderHeight +
                            '"></div>').insertAfter(ui.placeholder);
                    });

                },
                stop: function(e, ui) {

                    $(".block-placeholder-animator").remove();

                    var id = ui.item.attr('id');
                    var row_id = ui.item.closest('.row-data').attr('data');
                    var column_id = ui.item.closest('.column').attr('data');;

                    document.getElementById("item[" + id +"][row]").value = row_id;
                    document.getElementById("item[" + id +"][column]").value = column_id;

                    // Le añadimos la acciones

                    $('.block-actions',ui.item).remove();
                    ui.item.prepend(
                    `
                        <div class="block-actions pull-right">
                            <div class="remove modifier remove-block">
                                <i class="fa fa-times"></i>
                            </div>
                        </div>
                        `
                    );
                    removeBlock();
                },
            });

            $("#wrapper .blocks").on("sortreceive", function(event, ui) {

                var list = $(this);

                var column_id = list.closest('.column').attr('data');

                if (column_id > 0 && list.children(".block").length > 1) {
                    $(ui.sender).sortable('cancel');
                }
            });
        }

        // Rows
        var rows = {{ $row + 1 }};
        $('.row-add').click(function() {
            $('.builder-body').append(
                `
                <div class="row-data" data="${rows}">
                    <input id="row[${rows}][id]" name="row[${rows}][id]" type="hidden" value="${rows}">
                    <div class="row-toolbar col-12 unsortable">

                        <div class="column-selector">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fa fa-columns"></i>
                            </button>
                            <ul class="dropdown-menu row-columns dropdown">
                                <li role="presentation" class="column-option dropdown-item" data-split="1"><a role="menuitem" tabindex="-1" href="#">12</a></li>
                                <li role="presentation" class="column-option dropdown-item" data-split="2,2"><a role="menuitem" tabindex="-1" href="#">6/6</a></li>
                                <li role="presentation" class="column-option dropdown-item" data-split="3,3,3"><a role="menuitem" tabindex="-1" href="#">4/4/4</a></li>
                                <li role="presentation" class="column-option dropdown-item" data-split="4,4,4,4"><a role="menuitem" tabindex="-1" href="#">3/3/3/3</a></li>
                            </ul>

                        </div>

                        <div class="row-actions float-right">
                            <div class="remove remove-row">
                                <button class="btn btn-danger" type="button">
                                    <i class="fa fa-times"></i>
                                </button>

                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default panel-body sortable">
                        <div class="column-container row">
                            <div data="1" class="col-12 column sortable">
                                <input id="row[${rows}][columns][]" name="row[${rows}][columns][]" type="hidden" value="1">
                                <div class="blocks panel panel-default panel-body">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `
            );
            columOptions();
            sortBlocks();
            removeBlock();

            rows++;
        });



        $.fn.extend({
            removeclasser: function(re) {
                return this.each(function() {
                    var c = this.classList
                    for (var i = c.length - 1; i >= 0; i--) {
                        var classe = "" + c[i]
                        if (classe.match(re)) c.remove(classe)
                    }
                })
                return re;
            },
            translatecolumn: function(columns) {
                var grid = [];
                var items = columns.split(',');
                for (i = 0; i < items.length; ++i) {
                    if (items[i] == '1') {
                        grid.push(12);
                    }
                    if (items[i] == '2') {
                        grid.push(6);
                    }
                    if (items[i] == '3') {
                        grid.push(4);
                    }
                    if (items[i] == '4') {
                        grid.push(3);
                    }
                }
                return grid;
            }
        });


        $(".row-toolbar").disableSelection();

        function columOptions() {
            $('.column-option').click(function() {

                var grid = $.fn.translatecolumn($(this).data('split').toString());
                var row = $(this).closest('.row-data');
                var row_id = row.attr('data');
                var column_container = row.find('.column-container').first();
                var columns = $(this).closest('.row-data').find('.column');


                // Primeramente ponemos todas las columnas a 12
                var total_columns = columns.length;
                for (i = 0; i < total_columns ; ++i) {
                    $(columns[i]).removeclasser('col-');
                    $(columns[i]).addClass('col-12');
                }

                var grid_columns = grid.length;
                for (i = 0; i < grid_columns; ++i) {
                    if (columns[i]) {
                        $(columns[i]).removeclasser('col-');
                        $(columns[i]).addClass('col-' + grid[i]);
                    } else {
                        // Create column with class
                        $(column_container).append(`
                            <div data="${i+1}" class="col-${grid[i]} column sortable">
                                <input id="row[${row_id}][columns][]" name="row[${row_id}][columns][]" type="hidden" value="${i+1}">
                                <div class="blocks panel panel-default panel-body">

                                </div>
                            </div>
                        `);
                    }
                    // If less columns than existing then merge
                }

                for(i = grid_columns; i< total_columns; i++) {
                    // Obtenemos el bloque a mover a la columna de no ordenados

                    $('.block', $(columns[i])).each(function () {
                        moveBlock($(this));
                    });

                    $(columns[i]).remove();
                }

                sortBlocks();
            });
        }

        function removeBlock() {
            $('.remove-block').click(function() {
                // Obtenemos el bloque a mover a la columna de no ordenados
                var block = $(this).closest('.block');

                moveBlock(block);

            });

        }

        function moveBlock (block) {
            // Obtenemos el contenedor de columnas no ordenadas
            var contenedor = $("#0 .blocks");

            // Movemos el bloque
            block.appendTo(contenedor);

            // Quitamos los elementos sobrantes y modificamos su fila y columna
            $('.block-actions',block).remove();

            var id = block.attr('id');
            var row_id = block.closest('.row-data').attr('data');
            var column_id = block.closest('.column').attr('data');

            document.getElementById("item[" + id +"][row]").value = row_id;
            document.getElementById("item[" + id +"][column]").value = column_id;
        }

        function removeRow() {
            $('.remove-row').click(function() {
                // Obtenemos la file borrar
                var row = $(this).closest('.row-data');

                $('.block', row).each(function () {
                    moveBlock($(this));
                });

                row.remove();
            });

        }

        sortBlocks();
        columOptions();
        removeBlock();
        removeRow();

    </script>
@stop
