@extends('front.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('breadcrumb')
@foreach ($breadcrumb as $b)
    <li><a  href="{{url($b->generate_url ?: '#')}}" class="breadcrumb-page-module-active text-capitalize">{!! $b->title !!}</a></li>
@endforeach

<li class="active">{{ $page_title }}</li>
@endsection

@section('metas')
    <meta name="title" content="{!! $page->meta_title !!}">
    <meta name="description" content="{!! $page->meta_content !!}">

    @foreach($a_metas_providers as $key=>$value)

        <!-- {{ $key }} -->
        @foreach($value[config('app.locale')] as $prop => $valor)
            {!! '<meta '.config("social.".$key.".meta.property").'="'.$prop.'" content="'.$valor.'">' !!}
        @endforeach

    @endforeach

@stop

@section('head_page')
    @if(!empty($page->css))
        <style>
            {!! $page->css !!}
        </style>
    @endif
@stop

@section('content')

    <!-- Page Content -->
    <div class="container">

        {!! $page->body !!}



    </div>
    <!-- /.container -->
@endsection

@section("foot_page")
    @if(!empty($page->javascript))
        <script>
            {!! $page->javascript !!}
        </script>
    @endif
@stop
