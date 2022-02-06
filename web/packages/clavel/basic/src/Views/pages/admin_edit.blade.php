@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')
    <link href="{{ asset("assets/admin/vendor/select2/css/select2.min.css") }}" rel="stylesheet" type="text/css" />
    <style>
        #bs-modal-images, #bs-modal-code {
            z-index: 99999999;
        }

        /* SELECT2 */
        .select2-container--default .select2-selection--multiple { height: auto !important; }
        li.select2-selection__choice { color: black!important; }
    </style>
@stop

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url("admin/pages") }}">{{ trans('basic::pages/admin_lang.pages') }}</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')

    @include('admin.includes.errors')
    @include('admin.includes.success')
    @include('admin.includes.modals')

    <!-- Imágenes multimedia  -->
    <div class="modal modal-note fade in" id="bs-modal-images">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('basic::pages/admin_lang.selecciona_un_archivo') }}</h4>
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                </div>
                <div id="responsibe_images" class="modal-body">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <!-- Código fuente -->
    <div class="modal modal-code fade in" id="bs-modal-code">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('basic::pages/admin_lang.codigo') }}</h4>
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info" style="margin-right: 5px;" aria-hidden="true"></i> {{ trans("basic::pages/admin_lang.info_save_code") }}
                    </div>

                    <div id="editor" style="height: 500px;"></div>
                </div>
                <div class="modal-footer">

                    <a data-dismiss="modal" class="btn btn-default pull-left">{{ trans('general/admin_lang.cancelar') }}</a>
                    <button onclick="javascript:changeEditor();" class="btn btn-primary float-right">{{ trans('general/admin_lang.change') }}</button>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

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

    {!! Form::model($page, $form_data, array('role' => 'form')) !!}
    {!! Form::hidden('css', null, array('id' => 'css')) !!}
    {!! Form::hidden('javascript', null, array('id' => 'javascript')) !!}
    {!! Form::hidden('permission_name', null, array('id' => 'permission_name')) !!}
    <div class="row">


        <div class="col-md-10">
            <div class="card card-primary card-outline">
                <div class="card-header  with-border">
                    <h3 class="card-title">{{ trans("basic::pages/admin_lang.info_menu") }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        {!! Form::label('active', trans('basic::pages/admin_lang.status'), array('class' => 'col-sm-2 control-label', 'readonly' => true)) !!}

                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                {!! Form::radio('active', '0', true, array('id'=>'active_0', 'class' => 'form-check-input'))!!}
                                {!! Form::label('active_0', trans('general/admin_lang.no'), array('class' => 'form-check-label')) !!}
                            </div>
                            <div class="form-check form-check-inline">
                                {!! Form::radio('active', '1', false, array('id'=>'active_1', 'class' => 'form-check-input')) !!}
                                {!! Form::label('active_1', trans('general/admin_lang.yes'), array('class' => 'form-check-label')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <?php
                            $nX = 1;
                            ?>
                        @foreach ($a_trans as $key => $valor)
                        <li class="nav-item">
                            <a class="nav-link  @if($nX==1) active @endif" id="tabs-{{ $key }}-tab" data-toggle="pill"
                                href="#tabs-{{ $key }}" role="tab" aria-controls="tabs-{{ $key }}" aria-selected="true">
                                {{ $valor["idioma"] }}
                                @if($nX==1)- <span
                                    class="text-success">{{ trans('basic::pages/admin_lang._defecto') }}</span>@endif
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
                        <div class="tab-pane fade show @if($nX==1) active @endif" id="tabs-{{ $key }}" role="tabpanel"
                            aria-labelledby="tabs-{{ $key }}-tab">
                            {!! Form::hidden('userlang['.$key.'][id]', $valor["id"], array('id' => 'id')) !!}
                            {!! Form::hidden('userlang['.$key.'][page_id]', $page->id, array('id' => 'page_id')) !!}

                            <div class="form-group row">
                                {!! Form::label('userlang['.$key.'][title]', trans('basic::pages/admin_lang.title'),
                                array('class'
                                => 'col-sm-2 control-label')) !!}
                                <div class="col-sm-10">
                                    {!! Form::text('userlang['.$key.'][title]', $page->{'title:'.$key} , array('placeholder'
                                    =>
                                    trans('basic::pages/admin_lang._INSERTAR_title'), 'class' => 'form-control textarea',
                                    'id' =>
                                    'title_'.$key)) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('userlang['.$key.'][url_seo]', trans('basic::pages/admin_lang.url_seo'),
                                array('class' => 'col-sm-2 control-label')) !!}
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="form-control">{{ url('') }}/</span>
                                        {!! Form::text('userlang['.$key.'][url_seo]', "pages/".$page->{'url_seo:'.$key} ,
                                        array('placeholder' => trans('basic::pages/admin_lang._INSERTAR_url_seo'), 'class'
                                        =>
                                        'form-control textarea', 'readonly' => true, 'id' => 'url_seo_'.$key)) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('userlang['.$key.'][body]', trans('basic::pages/admin_lang.descripcion'),
                                array('class' => 'col-sm-2 control-label')) !!}
                                <div class="col-sm-10">
                                    {!! Form::textarea('userlang['.$key.'][body]', $page->{'body:'.$key} , array('class' =>
                                    'form-control textarea', 'id' => 'body_'.$key)) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('meta', trans('basic::pages/admin_lang.metatags'), array('class' => 'col-sm-2 control-label')) !!}
                                <div class="col-sm-10">
                                    <div id="accordion_{{ $key }}">

                                        <div class="card card-outline card-primary collapsed-card">
                                            <div class="card-header" data-card-widget="collapse">
                                                <h3 class="card-title">
                                                    {{ trans('basic::pages/admin_lang.metadata') }}
                                                </h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body" id="meta_{{ $key }}">


                                                <div class="form-group row">
                                                    {!! Form::label('userlang['.$key.'][meta_title]',
                                                    trans('basic::pages/admin_lang.meta_title'), array('class' =>
                                                    'col-sm-12')) !!}
                                                    <div class="col-sm-12">
                                                        {!! Form::text('userlang['.$key.'][meta_title]',
                                                        $page->{'meta_title:'.$key} , array('placeholder' =>
                                                        trans('basic::pages/admin_lang._INSERTAR_meta_title'), 'class' =>
                                                        'form-control textarea', 'id' => 'meta_title_'.$key)) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    {!! Form::label('userlang['.$key.'][meta_content]',
                                                    trans('basic::pages/admin_lang.meta_content'), array('class' =>
                                                    'col-sm-12')) !!}
                                                    <div class="col-sm-12">
                                                        {!! Form::textarea('userlang['.$key.'][meta_content]',
                                                        $page->{'meta_content:'.$key} , array('class' => 'form-control',
                                                        'id' => 'meta_content_'.$key, 'style' => 'resize:none; height:
                                                        100px;')) !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        @foreach(config("social") as $keysocial => $valuesocial)
                                        @if($valuesocial["meta"]["active"] == '1')
                                        <div class="card card-outline card-primary collapsed-card">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <i class="{{ $valuesocial["ico"] }}"></i>
                                                    {{ $valuesocial["meta"]["label"] }}
                                                </h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool"
                                                        data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body" id="meta_{{ $keysocial }}_{{ $key }}">


                                                @foreach($valuesocial["meta"]["inputs"] as $keyinput => $input)

                                                <div class="form-group row">
                                                    {!! Form::label('provider['.$keysocial.']['.$key.']['.$keyinput.']',
                                                    trans('general/admin_lang.'.$input["label"]), array('class' =>
                                                    'col-sm-12')) !!}
                                                    <div class="col-sm-12">
                                                        @if($input["isimage"]=='1') <div class="input-group"> @endif
                                                            <?php
                                                                $tipo = $input["type"];
                                                                ?>
                                                            {!!
                                                            Form::$tipo('provider['.$keysocial.']['.$key.']['.$keyinput.']',
                                                            (isset($a_metas_providers[$keysocial][$key][$keyinput])) ?
                                                            $a_metas_providers[$keysocial][$key][$keyinput] : null,
                                                            array('placeholder' =>
                                                            trans('basic::pages/admin_lang._INSERTAR_data'), 'class' =>
                                                            'form-control', "style" => ($input["type"]=='textarea') ?
                                                            "height:100px; resize:none;" : null, 'id' =>
                                                            'meta_title_'.$key.'_'.$keysocial."_".str_replace(":","",$keyinput)))
                                                            !!}
                                                            @if($input["isimage"]=='1')
                                                            <span class="input-group-btn">
                                                                <button class="btn bg-olive btn-flat"
                                                                    onclick="javascript:openImageController('{{ 'meta_title_'.$key.'_'.$keysocial."_".str_replace(":","",$keyinput) }}', '1');"
                                                                    type="button">{{ trans('basic::pages/admin_lang.selecciona_una_image') }}</button>
                                                            </span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                @endforeach

                                            </div>
                                        </div>
                                        @endif
                                        @endforeach

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
            </div>

            <div class="card">

                <div class="card-footer">

                    <a href="{{ url('/admin/pages') }}"
                        class="btn btn-secondary">{{ trans('general/admin_lang.cancelar') }}</a>
                    <button type="submit"
                        class="btn btn-primary float-right">{{ trans('general/admin_lang.save') }}</button>
                    <a href="#" onclick="javascript:showPreview();" class="btn btn-success float-right"
                        style="margin-right: 10px;">{{ trans('general/admin_lang.previa') }}</a>

                </div>

            </div>


        </div>

        <div class="col-md-2">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-key"></i> {{ trans("basic::pages/admin_lang.permisos") }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="form-check form-check-inline">
                                {!! Form::radio('permission', 0, true, array('id'=>'permission_0', 'class' => 'form-check-input')) !!}
                                {!! Form::label('permission_0', __('basic::pages/admin_lang.sin_permioss'), array('class' => 'form-check-label')) !!}
                            </div> <br>
                            <div class="form-check form-check-inline">
                                {!! Form::radio('permission', 1, false, array('id'=>'permission_1', 'class' => 'form-check-input')) !!}
                                {!! Form::label('permission_1', __('basic::pages/admin_lang.permisos_select'), array('class' => 'form-check-label')) !!}
                            </div>

                            <div id="roles"
                                style="@if(is_null($page->permission) || $page->permission=='0') display: none; @endif">
                                <div id="sel_roles" class="selector-roles" style="margin-left: 20px;">
                                    <br clear="all">
                                    <select class="form-control select2" name="sel_roles[]" multiple="multiple"
                                        data-placeholder="{{ trans('basic::pages/admin_lang.seleccion_roles') }}"
                                        style="width: 100%;">
                                        @foreach($roles as $value)
                                        <option value="{{ $value->id }}" @if($value->pagesSelected($page->id)) selected
                                            @endif>{{ $value->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary card-outline">
                <div class="card-header  with-border">
                    <h3 class="card-title"><i class="fas fa-cog"></i> {{ trans("basic::pages/admin_lang.avanzadas") }}</h3>
                </div>
                <div class="card-body">
                    <div style="margin-top: 5px;">
                        <a href="javascript:openFormAdvance('javascript');"><i class="fab fa-js" style="margin-right: 5px;" aria-hidden="true"></i> {{ trans("basic::pages/admin_lang.cambiar_javascript") }}</a>
                    </div>
                    <div style="margin-top: 10px; margin-bottom: 10px;">
                        <a href="javascript:openFormAdvance('css');"><i class="fab fa-css3" style="margin-right: 5px;"></i>
                            {{ trans('basic::pages/admin_lang.cambiar_css') }}</a>
                    </div>
                </div>
            </div>
        </div>


    </div>
    {!! Form::close() !!}

@endsection

@section("foot_page")
<script type="text/javascript" src="{{ asset('assets/admin/vendor/select2/js/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset("assets/admin/vendor/tinymce/tinymce.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("assets/admin/vendor/ace-builds/ace.js") }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script>
        var gtypeShow = "";
        var editor = ace.edit("editor");
        editor.setTheme("ace/theme/monokai");

        $(document).ready(function() {
            tinymce.init({
                selector: "textarea.textarea",
                menubar: false,
                height: 300,
                resize:false,
                convert_urls: false,
                extended_valid_elements : "a[class|name|href|target|title|onclick|rel],script[type|src],iframe[src|style|width|height|scrolling|marginwidth|marginheight|frameborder],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],$elements",
                plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table paste hr",
                    "wordcount fullscreen nonbreaking visualblocks"
                ],

                content_css: [
                    /* // Ponemos aquí los css de front
                    '{{ url('assets/front/vendor/bootstrap/css/bootstrap.min.css') }}',
                    '{{ url('assets/front/vendor/fontawesome/css/font-awesome.min.css') }}',
                    '{{ url('assets/front/css/front.min.css') }}',
                    '{{ url('assets/front/css/theme.css') }}',
                    '{{ url('assets/front/css/theme-element.css') }}',
                    '{{ url('assets/front/vendor/fontawesome/css/font-awesome.min.css') }}' */
                ],
                toolbar: "insertfile undo redo | styleselect | fontsizeselect | bold italic forecolor, backcolor | hr nonbreaking visualblocks | table | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link media image | code fullscreen",
                file_picker_callback: function(callback, value, meta) {
                    openImageControllerExt(callback, '0');
                }
            });

            $("#permission_0").click(function() {
                $("#roles").slideUp(500);
            });

            $("#permission_1").click(function() {
                $("#roles").slideDown(500);
            });

            $(".select2").select2();
        });

        function evtHidden(evt) {
            evt.data($("#selectedFile").val());
        }

        function openImageControllerExt(callback, only_img) {
            $('#bs-modal-images')
                .one('hidden.bs.modal', callback, evtHidden)
                .modal({
                keyboard: false,
                backdrop: 'static',
                show: true
            });

            var style = "width: 100%;padding: 50px; text-align: center;";
            //$("#responsibe_images").html('<div id="spinner" class="overlay" style="'+style+'"><i class="fa fa-refresh fa-spin"></i></div>');
            $("#responsibe_images").html('<div class="overlay d-f justify-content-center align-items-center bg-white w-100 py-5"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2"></div></div>');
            $("#responsibe_images").load("{{ url("admin/media/viewer-simple/") }}/" + only_img);
        }

        function openFormAdvance(typeShow) {
            $('#bs-modal-code').modal({
                keyboard: false,
                backdrop: 'static',
                show: true
            });
            gtypeShow = typeShow;

            editor.getSession().setMode("ace/mode/" + typeShow);
            editor.setValue($("#" + typeShow).val());
        }

        function changeEditor() {
            $("#" + gtypeShow).val(editor.getValue());
            gtypeShow = "";
            $("#bs-modal-code").modal('hide');
        }

        function showPreview() {
            $('#bs-modal-preview').modal({
                keyboard: false,
                backdrop: 'static',
                show: true
            });
            
            $("#content-preview").html('<div class="overlay d-f justify-content-center align-items-center bg-white w-100 py-5"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2"></div></div>');
           
            $.ajax({
                url: '{{ url("admin/pages/preview") }}',
                type: 'POST',
                data: {
                    'title': $("#title_{{ app()->getLocale() }}").val(),
                    'css': $("#css").val(),
                    'javascript': $("#javascript").val(),
                    'body': $("#body_{{ app()->getLocale() }}").val()
                },
                headers: { 'X-CSRF-Token': '{{ csrf_token() }}' },
                success: function(result){
                    $("#content-preview").html(result);
                }
            });


        }
    </script>
    {!! JsValidator::formRequest('Clavel\Basic\Requests\AdminPagesRequest')->selector('#formData') !!}

@stop
