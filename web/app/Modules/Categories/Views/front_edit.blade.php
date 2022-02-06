@extends('front.layouts.default')

@section('title')
@parent {{ $page_title }}
@stop

@section('head_page')
<link href="{{  asset('/assets/front/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"
    type="text/css" />
<link rel=“stylesheet” href="{{ asset("
    assets/admin/vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") }}">

@stop

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ url("front/categories") }}">{{
        trans('Categories::categories/front_lang.category') }}</a></li>
<li class="breadcrumb-item active"><a href="#">{{ $page_title }}</a></li>
@stop

@section('content')


@include('front.includes.errors')
@include('front.includes.success')

{!! Form::model($category, $form_data, array('role' => 'form')) !!}
{!! Form::hidden("form_return", 0, array('id' => 'form_return')) !!}
<div class="row">

    <div class="col-12">

        <div class="card card-featured-top card-featured-primary">
            <div class="card-header with-border">
                <h3 class="card-title">{{ trans("Categories::categories/front_lang.info_menu") }}</h3>
            </div>
            <div class="card-body">
                <div class="row form-group pb-3">

                    <div data-row="1" data-col="1" class="col-12">
                        {{-- Text - name --}}
                        <div class="form-group row">
                            {!! Form::label('name', trans('Categories::categories/front_lang.fields.name'),
                            array('class' => 'col-sm-2 col-form-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('name', null , array('placeholder' =>
                                trans('Categories::categories/front_lang.fields.name_helper'), 'class' =>
                                'form-control', 'id' => 'name')) !!}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row form-group pb-3">

                    <div data-row="2" data-col="1" class="col-12">
                        {{-- TextArea - description --}}
                        <div class="form-group row">
                            {!! Form::label('description',
                            trans('Categories::categories/front_lang.fields.description'), array('class' => 'col-sm-2
                            col-form-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::textarea('description', null , array('class' => 'form-control textarea', 'id'
                                => 'description')) !!}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row form-group pb-3">

                    <div data-row="3" data-col="1" class="col-12">
                        {{-- Radio yes/no - active --}}
                        <div class="form-group row">
                            {!! Form::label('active', trans('Categories::categories/front_lang.fields.active'),
                            array('class' => 'col-sm-2 col-form-label')) !!}
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    <label>
                                        {!! Form::radio('active', 0, true, array('id'=>'active_0', 'class' =>
                                        'form-check-input')) !!}
                                        {!! Form::label('active_0', trans('general/front_lang.no'), array('class' =>
                                        'form-check-label')) !!}
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label>
                                        {!! Form::radio('active', 1, false, array('id'=>'active_1', 'class' =>
                                        'form-check-input')) !!}
                                        {!! Form::label('active_1', trans('general/front_lang.yes'), array('class' =>
                                        'form-check-label')) !!}
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        @if(\View::exists('Categories::front_edit_lang'))
        @include('Categories::front_edit_lang')
        @endif

        <div class="card box-solid">
            <div class="card-footer">
                <a href="{{ url('/front/categories') }}" class="btn btn-secondary">{{
                    trans('general/front_lang.cancelar') }}</a>
                <button type="submit" class="btn btn-primary pull-right">{{ trans('general/front_lang.save') }}</button>
                <button id="btnSaveReturn" class="btn btn-success pull-right" style="margin-right:20px;">{{
                    trans('general/front_lang.save_and_return') }}</button>
            </div>
        </div>

    </div>

</div>
{!! Form::close() !!}

<!-- Imágenes multimedia  -->
<div class="modal modal-note fade in" id="bs-modal-images">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                        aria-hidden="true">×</span></button>
                <h4 class="modal-title">{{ trans('basic::pages/front_lang.selecciona_un_archivo') }}</h4>
            </div>
            <div id="responsibe_images" class="modal-body">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


@endsection

@section("foot_page")
<script type="text/javascript" src="{{ asset(" assets/admin/vendor/tinymce/tinymce.min.js") }}"> </script>

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>


<script>
    $(document).ready(function() {
            tinymce.init({
    selector: "textarea.textarea",
    menubar: false,
    height: 300,
    resize:false,
    convert_urls: false,
    // extended_valid_elements : "a[class|name|href|target|title|onclick|rel],script[type|src],iframe[src|style|width|height|scrolling|marginwidth|marginheight|frameborder],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name]",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table paste hr",
        "wordcount fullscreen nonbreaking visualblocks"
    ],
    content_css: [
        {{--
        // Ponemos aquí los css de front
        '{{ url('assets/front/vendor/bootstrap/css/bootstrap.min.css') }}',
        '{{ url('assets/front/vendor/fontawesome/css/font-awesome.min.css') }}',
        '{{ url('assets/front/css/front.min.css') }}',
        '{{ url('assets/front/css/theme.css') }}',
        '{{ url('assets/front/css/theme-element.css') }}',
        '{{ url('assets/front/vendor/fontawesome/css/font-awesome.min.css') }}'
        --}}
        ],
    toolbar: "insertfile undo redo | styleselect | fontsizeselect | bold italic forecolor, backcolor | hr nonbreaking visualblocks | table |  alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link media image | code fullscreen",
    file_picker_callback: function(callback, value, meta) {
        openImageControllerExt(callback, '0');
    }
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
    $("#responsibe_images").html('<div id="spinner" class="overlay" style="'+style+'"><i class="fa fa-refresh fa-spin"></i></div>');
    $("#responsibe_images").load("{{ url("front/media/viewer-simple/") }}/" + only_img);
}

        });

        $('#btnSaveReturn').on( 'click', function (event) {
            event.preventDefault();
            $('#form_return').val(1);
            $('#formData').submit();
        } );

</script>
{!! JsValidator::formRequest('App\Modules\Categories\Requests\FrontCategoriesRequest')->selector('#formData') !!}
@stop