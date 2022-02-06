@extends('front.layouts.default')

@section('title')
@parent {{ $page_title }}
@stop

@section('head_page')

@stop

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ url("front/products") }}">{{ trans('Products::products/front_lang.product')
        }}</a></li>
<li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')


@include('front.includes.errors')
@include('front.includes.success')

{!! Form::model($product, $form_data, array('role' => 'form')) !!}
{!! Form::hidden("form_return", 0, array('id' => 'form_return')) !!}
<div class="row">

    <div class="col-12">

        <div class="card card-featured-top card-featured-primary">
            <div class="card-header with-border">
                <h3 class="card-title">{{ trans("Products::products/front_lang.info_menu") }}</h3>
            </div>
            <div class="card-body">
                @if (!empty($product->id))
                <div class="row form-group pb-3">

                    <div data-row="1" data-col="1" class="col-12">
                        {{-- Text - code --}}
                        <div class="form-group row">
                            {!! Form::label('code', trans('Products::products/front_lang.fields.code'), array('class' =>
                            'col-sm-2 col-form-label')) !!}
                            <div class="col-sm-4">
                                {!! Form::text('code', null , array('placeholder' =>
                                trans('Products::products/front_lang.fields.code_helper'), 'class' => 'form-control',
                                'id' => 'code','readonly'=>'readonly')) !!}
                            </div>
                        </div>

                    </div>
                </div>
                @endif
                <div class="row form-group pb-3">

                    <div data-row="2" data-col="1" class="col-12">
                        {{-- belongsToRelationship - category --}}
                        <div class="form-group row">
                            {!! Form::label('category_id', trans('Products::products/front_lang.fields.category'),
                            array('class' => 'col-sm-2 col-form-label')) !!}
                            <div class="col-sm-10">
                                @php
                                $items = [];
                                @endphp
                                @foreach($categories as $id => $category)
                                @php
                                $items+= [ $id => $category]
                                @endphp
                                @endforeach
                                {!! Form::select('category_id',
                                ['' => trans('Products::products/front_lang.fields.category_helper')] +
                                $items
                                ,
                                null ,
                                ['id'=>'category_id', 'class' => 'form-control select2']) !!}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row form-group pb-3">

                    <div data-row="3" data-col="1" class="col-12">
                        {{-- Text - name --}}
                        <div class="form-group row">
                            {!! Form::label('name', trans('Products::products/front_lang.fields.name'), array('class' =>
                            'col-sm-2 col-form-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('name', null , array('placeholder' =>
                                trans('Products::products/front_lang.fields.name_helper'), 'class' => 'form-control',
                                'id' => 'name')) !!}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row form-group pb-3">

                    <div data-row="3" data-col="1" class="col-12">
                        {{-- Text - name --}}
                        <div class="form-group row">
                            {!! Form::label('amount', trans('Products::products/front_lang.fields.amount'),
                            array('class' =>
                            'col-sm-2 col-form-label')) !!}
                            <div class="col-sm-4">
                                {!! Form::text('amount', null , array('placeholder' =>
                                trans('Products::products/front_lang.fields.amount_helper'), 'class' => 'form-control',
                                'id' => 'amount')) !!}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row form-group pb-3">

                    <div data-row="4" data-col="1" class="col-12">
                        {{-- Money - price --}}
                        <div class="form-group row">
                            {!! Form::label('price', trans('Products::products/front_lang.fields.price'), array('class'
                            => 'col-sm-2 col-form-label')) !!}
                            <div class="col-sm-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="fas fa-euro-sign"></i>
                                    </span>
                                    {!! Form::text('price', null , array('placeholder' =>
                                    trans('Products::products/front_lang.fields.price_helper'), 'class' =>
                                    'form-control', 'id' => 'price')) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row form-group pb-3">

                    <div data-row="5" data-col="1" class="col-12">
                        {{-- Radio yes/no - has_taxes --}}
                        <div class="form-group row">
                            {!! Form::label('has_taxes', trans('Products::products/front_lang.fields.has_taxes'),
                            array('class' => 'col-sm-2 col-form-label')) !!}
                            <div class="col-md-10">
                                <div class="form-check form-check-inline">
                                    <label>
                                        {!! Form::radio('has_taxes', 0, true, array('id'=>'has_taxes_0', 'class' =>
                                        'form-check-input')) !!}
                                        {!! Form::label('has_taxes_0', trans('general/front_lang.no'), array('class' =>
                                        'form-check-label')) !!}
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label>
                                        {!! Form::radio('has_taxes', 1, false, array('id'=>'has_taxes_1', 'class' =>
                                        'form-check-input')) !!}
                                        {!! Form::label('has_taxes_1', trans('general/front_lang.yes'), array('class' =>
                                        'form-check-label')) !!}
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row form-group pb-3">

                    <div data-row="6" data-col="1" class="col-12">
                        {{-- Float - taxes --}}
                        <div class="form-group row">
                            {!! Form::label('taxes', trans('Products::products/front_lang.fields.taxes'), array('class'
                            => 'col-sm-2 col-form-label')) !!}
                            <div class="col-sm-4">
                                {!! Form::text('taxes', null , array('placeholder' =>
                                trans('Products::products/front_lang.fields.taxes_helper'), 'class' => 'form-control',
                                'id' => 'taxes')) !!}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row form-group pb-3">

                    <div data-row="7" data-col="1" class="col-12">
                        {{-- Money - real_price --}}
                        <div class="form-group row">
                            {!! Form::label('real_price', trans('Products::products/front_lang.fields.real_price'),
                            array('class' => 'col-sm-2 col-form-label')) !!}
                            <div class="col-sm-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="fas fa-euro-sign"></i>
                                    </span>
                                    {!! Form::text('real_price', null , array('placeholder' =>
                                    trans('Products::products/front_lang.fields.real_price_helper'), 'class' =>
                                    'form-control', 'id' => 'real_price','readonly'=>'readonly')) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row form-group pb-3">

                    <div data-row="8" data-col="1" class="col-12">
                        {{-- TextArea - description --}}
                        <div class="form-group row">
                            {!! Form::label('description', trans('Products::products/front_lang.fields.description'),
                            array('class' => 'col-sm-2 col-form-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::textarea('description', null , array('class' => 'form-control textarea', 'id'
                                => 'description')) !!}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row form-group pb-3">

                    <div data-row="9" data-col="1" class="col-12">
                        {{-- Radio yes/no - active --}}
                        <div class="form-group row">
                            {!! Form::label('active', trans('Products::products/front_lang.fields.active'),
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

        @if(\View::exists('Products::front_edit_lang'))
        @include('Products::front_edit_lang')
        @endif

        <div class="card box-solid">
            <div class="card-footer">
                <a href="{{ url('/front/products') }}" class="btn btn-secondary">{{ trans('general/front_lang.cancelar')
                    }}</a>
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
<script type="text/javascript" src="{{ asset(" assets/front/vendor/tinymce/tinymce.min.js") }}">
</script>
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
{!! JsValidator::formRequest('App\Modules\Products\Requests\FrontProductsRequest')->selector('#formData') !!}
@stop