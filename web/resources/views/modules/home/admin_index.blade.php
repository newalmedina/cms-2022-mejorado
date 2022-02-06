@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')


@stop

@section('pre-content')


@endsection

@section('content')

<?php
// Cargamos el resto de puntos de menu
$files = new \Illuminate\Filesystem\Filesystem;

$dashboardPath = base_path('resources/views/admin/includes/dashboard/');

if ($files->isDirectory($dashboardPath)) {

    foreach ($files->allFiles($dashboardPath) as $file) {
        ?>
        @include('admin.includes.dashboard.'.basename($file, ".blade.php"))
        <?php
    }
}
?>

@endsection

@section('foot_page')

@stop
