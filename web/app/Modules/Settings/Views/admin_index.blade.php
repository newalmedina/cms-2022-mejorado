@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')
    @yield('tab_head')
@stop

@section('breadcrumb')

    <li class="breadcrumb-item">
        <a href="{{ url('admin/settings') }}">
            {{ $page_title }}
        </a>
    </li>

    @yield('tab_breadcrumb')
@stop

@section('content')


    @include('admin.includes.errors')
    @include('admin.includes.success')

    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a id="tab_1" class="nav-link @if ($tab == 'tab_1') active @endif" role="tab" data-toggle="tabajax"
                                href="{{ url('admin/settings') }}" data-target="#tab_1-1" aria-controls="tab_1-1"
                                aria-selected="true">
                                {{ trans('Settings::admin_lang.info_menu') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="tab_2" class="nav-link @if ($tab == 'tab_2') active @endif" role="tab" data-toggle="tabajax"
                                href="{{ url('admin/settings/mail') }}" data-target="#tab_2-2" aria-controls="tab_2-2"
                                aria-selected="true">
                                {{ trans('Settings::admin_lang.test_email') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="tab_tabContent">
                        <div id="tab_1-1" class="tab-pane fade show @if ($tab == 'tab_1') active @endif" role="tabpanel"
                            aria-labelledby="tab_1">
                            @yield('tab_content_1')
                        </div>
                        <!-- /.tab-pane -->

                        <div id="tab_2-2" class="tab-pane fade show @if ($tab == 'tab_2') active @endif" role="tabpanel"
                            aria-labelledby="tab_2">
                            @yield('tab_content_2')
                        </div><!-- /.tab-pane -->

                    </div><!-- /.tab-content -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('foot_page')

    @yield('tab_foot')

@stop
