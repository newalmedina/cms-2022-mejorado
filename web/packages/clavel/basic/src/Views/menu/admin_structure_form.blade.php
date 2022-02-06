<style>
    /* SELECT2 */
    .select2-container--default .select2-selection--multiple { height: auto !important; }
    li.select2-selection__choice { color: black!important; }
</style>
<link href="{{ asset("assets/admin/vendor/select2/css/select2.min.css") }}" rel="stylesheet" type="text/css" />

<!-- Vista previa -->
<div class="modal modal-preview fade in" id="bs-modal-preview">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('basic::pages/admin_lang.preview') }}</h4>
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            </div>
            <div id="content-preview" class="modal-body">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

{!! Form::model($item, $form_data, array('role' => 'form')) !!}
{!! Form::hidden('id', null, array('id' => 'id')) !!}
{!! Form::hidden('menu_id', $menu_id, array('id' => 'menu_id')) !!}

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">

            <div class="card-header with-border">
                <h3 class="card-title">
                    {{ trans("basic::menu/admin_lang.info_menu") }}@if($item->title!='') - {!!
                    $item->{'title:'.config("app.default_locale")} !!} @endif
                </h3>
            </div>

            <div class="card-body">

                <div class="form-group row">
                    {!! Form::label('item_type_id', trans('basic::menu/admin_lang.tipo_contenido'), array('class' =>
                    'col-sm-2
                    control-label', 'readonly' => true)) !!}
                    <div class="col-md-10">
                        <select id="item_type_id" name="item_type_id" class="form-control">
                            @foreach($idtypes as $type)
                            <option value="{{ $type->id }}" data-rel="{{ $type->slug }}" @if(isset($item->item_type_id)
                                &&
                                $item->item_type_id==$type->id) selected @endif>{{ $type->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="selector_pagina" class="selector" style=" display: none;">
                    <div class="form-group row">
                        {!! Form::label('page_id', trans('basic::menu/admin_lang.pagina'), array('class' => 'col-sm-2
                        control-label', 'readonly' => true)) !!}
                        <div class="col-md-8">
                            <select id="page_id" name="page_id" class="form-control">
                                <option value="">{{ trans("basic::menu/admin_lang.nothing") }}</option>
                                @foreach($pages as $page)
                                <option value="{{ $page->id }}" @if($page->id==$item->page_id) selected
                                    @endif>{{ $page->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a class="btn bg-purple btn-sm float-right" href="javascript:showPreview();"
                                data-content="{{ trans('general/admin_lang.ver') }}" data-placement="right"
                                data-toggle="popover"><i class="fa fa-search"></i> Vista previa</a>
                        </div>
                    </div>
                </div>

                <div id="selector_modulo" class="selector" style=" display: none;">
                    <div class="form-group row">
                        {!! Form::label('module_name', trans('basic::menu/admin_lang.module'), array('class' => 'col-sm-2
                        control-label', 'readonly' => true)) !!}
                        <div class="col-md-10">
                            <select id="module_name" name="module_name" class="form-control">
                                <option value="">{{ trans("basic::menu/admin_lang.nothing") }}</option>
                                @foreach(config("modules.enable") as $modulo)
                                <option value="{{ $modulo["route"] }}" @if($item->module_name==$modulo["route"]) selected
                                    @endif>{{ $modulo["name"] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="selector_system" class="selector" style=" display: none;">
                    <div class="form-group row">
                        {!! Form::label('system_name', trans('basic::menu/admin_lang.fixed_menu'), array('class' =>
                        'col-sm-2
                        control-label', 'readonly' => true)) !!}
                        <div class="col-md-10">
                            <select id="system_name" name="system_name" class="form-control">
                                <option value="">{{ trans("basic::menu/admin_lang.nothing") }}</option>

                                <option value="language" @if($item->module_name=='language') selected @endif>Idiomas
                                </option>
                                <option value="profile_name" @if($item->module_name=='profile_name') selected @endif>Nombre
                                    usuario
                                </option>
                                <option value="divider" @if($item->module_name=='divider') selected @endif>División</option>
                                <option value="logout" @if($item->module_name=='logout') selected @endif>Cerrar sesión
                                </option>

                            </select>
                        </div>
                    </div>
                </div>

                <div id="selector_interno" class="selector" style=" display: none;">
                    <div class="form-group row">
                        {!! Form::label('uri', trans('basic::menu/admin_lang.uri'), array('class' => 'col-sm-2
                        control-label',
                        'readonly' => true)) !!}
                        <div class="col-md-10">
                            {!! Form::text('uri', null, array('placeholder' => trans('basic::menu/admin_lang.uri_insertar'),
                            'class'
                            => 'form-control', 'id' => 'uri')) !!}
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('target', trans('basic::menu/admin_lang.target'), array('class' => 'col-sm-2
                    control-label',
                    'readonly' => true)) !!}
                    <div class="col-md-10">
                        <select id="target" name="target" class="form-control">
                            <option value="" @if(isset($item->target) && $item->target==null) selected
                                @endif>{{ trans('basic::menu/admin_lang.same') }}</option>
                            <option value="_blank" @if(isset($item->target) && $item->target=='_blank') selected
                                @endif>{{ trans('basic::menu/admin_lang.newpage') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('status', trans('basic::menu/admin_lang.status'), array('class' => 'col-sm-2
                    control-label',
                    'readonly' => true)) !!}
                    <div class="col-md-10">
                        <div class="form-check form-check-inline">
                            {!! Form::radio('status', 0, true, ['id'=>'status_0', 'class' => 'form-check-input']) !!}
                            {{ Lang::get('general/admin_lang.no') }}</div>
                        <div class="form-check form-check-inline">
                            {!! Form::radio('status', 1, false, ['id'=>'status_1', 'class' => 'form-check-input']) !!}
                            {{ Lang::get('general/admin_lang.yes') }} </label>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                    <?php
                    $nX = 1;
                    ?>
                    @foreach ($a_trans as $key => $valor)
                    <li class="nav-item">
                        <a class="nav-link  @if($nX==1) active @endif" id="tabs-{{ $key }}-tab" data-toggle="pill"
                            href="#tabs-{{ $key }}"
                            role="tab"
                            aria-controls="tabs-{{ $key }}"
                            aria-selected="true">
                            {{ $valor["idioma"] }}
                            @if($nX==1)- <span class="text-success">{{ trans('basic::menu/admin_lang._defecto') }}</span>@endif
                        </a>
                    </li>
                    <?php
                    $nX++;
                    ?>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="tabsContent">
                    <?php
                    $nX = 1;
                    ?>
                    @foreach ($a_trans as $key => $valor)
                    <div class="tab-pane fade show @if($nX==1) active @endif"
                        id="tabs-{{ $key }}"
                        role="tabpanel"
                        aria-labelledby="tabs-{{ $key }}-tab">
                        {!!  Form::hidden('userlang['.$key.'][id]', $valor["id"], array('id' => 'id')) !!}
                        {!!  Form::hidden('userlang['.$key.'][menu_item_id]', $item->id, array('id' => 'menu_item_id')) !!}
                        <div class="form-group row">
                            {!! Form::label('userlang['.$key.'][title]', trans('basic::menu/admin_lang.title'), array('class' => 'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('userlang['.$key.'][title]', $item->{'title:'.$key} , array('placeholder' => trans('basic::menu/admin_lang._INSERTAR_title'), 'class' => 'form-control textarea', 'id' => 'title_'.$key)) !!}
                            </div>
                        </div>

                        <div id="selector" class="selector_externo" style=" display: none;">
                            <div class="form-group row">
                                {!! Form::label('userlang['.$key.'][url]', trans('basic::menu/admin_lang.url'), array('class' => 'col-sm-2 control-label')) !!}
                                <div class="col-sm-10">
                                    {!! Form::text('userlang['.$key.'][url]', $item->{'url:'.$key}, array('placeholder' => trans('basic::menu/admin_lang._INSERTAR_url'), 'class' => 'form-control textarea', 'id' => 'url_'.$key)) !!}
                                </div>
                            </div>
                        </div>

                        <div id="selector">
                            <div class="form-group row">
                                {!! Form::label('userlang['.$key.'][generate_url]', trans('basic::menu/admin_lang.url_generated'), array('class' => 'col-sm-2 control-label')) !!}
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span id="strUrl" class="form-control">{{ url('') }}/</span>
                                        {!! Form::text('userlang['.$key.'][generate_url]', $item->{'generate_url:'.$key}, array('readonly' => 'readonly', 'class' => 'form-control textarea', 'id' => 'generate_url_'.$key)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <?php
                    $nX++;
                    ?>
                    @endforeach
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header  with-border">
                <h3 class="card-title"><i class="fa fa-key"></i> {{ trans("basic::menu/admin_lang.permisos") }}</h3>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="form-check form-check-inline">
                            {!! Form::radio('permission', 0, true, array('id'=>'permission_0', 'class' => 'form-check-input'))!!}
                            {!! Form::label('permission_0', __('basic::menu/admin_lang.always_visible'), array('class' => 'form-check-label')) !!}
                        </div> <br>
                        <div class="form-check form-check-inline">
                            {!! Form::radio('permission', 2, true, array('id'=>'permission_2', 'class' => 'form-check-input')) !!}
                            {!! Form::label('permission_2', __('basic::menu/admin_lang.not_authentified'), array('class' => 'form-check-label')) !!}
                        </div> <br>
                        <div class="form-check form-check-inline">
                            {!! Form::radio('permission', 1, false, array('id'=>'permission_1', 'class' => 'form-check-input')) !!}
                            {!! Form::label('permission_1', __('basic::menu/admin_lang.only_authentified'), array('class' => 'form-check-label')) !!}
                        </div>

                        <div id="roles" style="@if($item->permission!='1') display: none; @endif">
                            <div id="sel_roles" class="selector-roles" style="margin-left: 20px;">
                                <br clear="all">
                                <select class="form-control select2 select2-purple" id="sel_roles_list" name="sel_roles[]"
                                    multiple="multiple"
                                    data-placeholder="{{ trans('basic::menu/admin_lang.seleccion_roles') }}"
                                    style="width: 100%;">
                                    @foreach($roles as $value)
                                    <option value="{{ $value->id }}" @if($value->menusSelected($item->id)) selected
                                        @endif>{{ $value->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">

    <div class="card-footer">

        <a href="javascript:formclose();" class="btn btn-secondary">{{ trans('general/admin_lang.close') }}</a>
        <button type="submit" class="btn btn-primary float-right">{{ trans('general/admin_lang.save') }}</button>

    </div>

</div>


{!! Form::close() !!}

    <script type="text/javascript" src="{{ asset('assets/admin/vendor/select2/js/select2.min.js')}}"></script>

<script>

    var viewerSelected = $( "#item_type_id option:selected" ).attr("data-rel");
    $(document).ready(function() {
        if(viewerSelected!='externo') {
            $("#selector_" + viewerSelected).css("display","block");
        } else {
            $(".selector_externo").css("display","block");
            $("#strUrl").css("text-decoration","line-through");
        }


        $( "#item_type_id").change(function() {
            changeOption();
        });

        $("#blocks_top_0, #blocks_right_0, #blocks_left_0, #blocks_bottom_0").click(function() {
            var divHider = $(this).attr('data-select');

            $("#" + divHider).slideUp(500);
        });

        $("#blocks_top_1, #blocks_right_1, #blocks_left_1, #blocks_bottom_1").click(function() {
            var divHider = $(this).attr('data-select');

            $("#" + divHider).slideDown(500);
        });

        $("#permission_0, #permission_2").click(function() {
            $("#roles").slideUp(500);
            $('#sel_roles_list').val('').trigger('change')
        });

        $("#permission_1").click(function() {
            $("#roles").slideDown(500);
        });

        $(".select2").select2();
    });

    function changeOption() {
        var viewer = $( "#item_type_id option:selected" ).attr("data-rel");



        if(viewer == 'externo') {
            $(".selector_externo").fadeIn(500);
            $("#selector_" + viewerSelected).fadeOut(200);
            viewerSelected = viewer;
            $("#strUrl").css("text-decoration","line-through");
        } else {
            $("#strUrl").css("text-decoration","none");
            if(viewerSelected=='externo') {
                $(".selector_externo").fadeOut(500);
                $("#selector_" + viewer).fadeIn(200);
                viewerSelected = viewer;
            } else {
                $("#selector_" + viewerSelected).fadeOut(200, function() {
                    viewerSelected = viewer;
                    $("#selector_" + viewer).fadeIn(500);
                });
            }

        }

    }

    function showPreview() {
        var page_id = $("#page_id").val();

        if(page_id!='') {
            $("#content-preview").html('<div id="spinner2" class="overlay" style="text-align: center"><i class="fa fa-refresh fa-spin" style="font-size: 64px;" aria-hidden="true"></i></div>');
            $('#bs-modal-preview').modal({
                keyboard: false,
                backdrop: 'static',
                show: true
            });
            $("#content-preview").load("{{ url("admin/pages/preview/") }}/" + page_id);
        }

    }


</script>
