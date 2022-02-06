@extends('admin.layouts.default')

@section('title')
    @parent {{ $page_title }}
@stop

@section('head_page')

@stop

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url("admin/users") }}">{{ trans('users/lang.usuarios') }}</a></li>
    <li class="breadcrumb-item active">{{ $page_title }}</li>
@stop

@section('content')

    @include('admin.includes.errors')
    @include('admin.includes.success')
    @include('admin.includes.modals')

    <div class="row">
        <div class="col-12">

            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a id="tab_1" class="nav-link active" role="tab"
                                data-toggle="tabajax"
                                href="{{ url('admin/users/edit_user/'.$id) }}"
                                data-target="#tab_1-1"
                                aria-controls="tab_1-1" aria-selected="true">
                                {{ trans('users/lang.datos_personales') }}
                            </a>
                        </li>
                        @if($id!=0 && (Auth::user()->isAbleTo('admin-users-update') || Auth::user()->isAbleTo('admin-users-read')))
                        <li>
                            <a id="tab_2" class="nav-link" role="tab" data-toggle="tabajax"
                                href="{{ url('admin/users/roles/'.$id) }}"
                                data-target="#tab_2-2" aria-controls="tab_2-2"
                                aria-selected="false">
                                {{ trans('users/lang.roles') }}
                            </a>
                        </li>
                        <li>
                            <a id="tab_3" class="nav-link" role="tab" data-toggle="tabajax"
                                href="{{ url('admin/users/social/'.$id) }}" data-target="#tab_3-3" aria-controls="tab_3-3"
                                aria-selected="false">
                                {{ trans('users/lang.social_title') }}
                            </a>
                        </li>
                        <li>
                            <a id="tab_5" class="nav-link" role="tab" data-toggle="tabajax"
                                href="{{ url('admin/users/api/'.$id) }}" data-target="#tab_5-5" aria-controls="tab_5-5"
                                aria-selected="false">
                                {{ trans('users/lang.api_title') }}
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="tab_tabContent">
                        <div id="tab_1-1" class="tab-pane fade show active" role="tabpanel" aria-labelledby="tab_1">
                            <div class="overlay-wrapper" style="height: 100px">
                                <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                    <div class="text-bold pt-2">Loading...</div>
                                </div>
                            </div>
                        </div><!-- /.tab-pane -->
                        @if($id!=0)
                        <div id="tab_2-2" class="tab-pane fade show" role="tabpanel" aria-labelledby="tab_2">
                            <div class="overlay-wrapper" style="height: 100px">
                                <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                    <div class="text-bold pt-2">Loading...</div>
                                </div>
                            </div>
                        </div><!-- /.tab-pane -->
                        <div id="tab_3-3" class="tab-pane fade show" role="tabpanel" aria-labelledby="tab_3">
                            <div class="overlay-wrapper" style="height: 100px">
                                <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                    <div class="text-bold pt-2">Loading...</div>
                                </div>
                            </div>
                        </div><!-- /.tab-pane -->
                        <div id="tab_5-5" class="tab-pane fade show" role="tabpanel" aria-labelledby="tab_5">
                            <div class="overlay-wrapper" style="height: 100px">
                                <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                    <div class="text-bold pt-2">Loading...</div>
                                </div>
                            </div>
                        </div><!-- /.tab-pane -->
                        @endif
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>

@endsection

@section("foot_page")
    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="tabajax"]').click(function(e) {
                loadTab($(this));
                return false;
            });

            @if (Session::get('tab',"") != "")
            loadTab($("#{!! Session::get('tab') !!}"));
            @else
            loadTab($("#tab_1"));
            @endif

        });

        function loadTab(obj) {
            var $this = obj,
                loadurl = $this.attr('href'),
                targ = $this.attr('data-target');

            $.get(loadurl, function(data) {
                $(targ).html(data);
            });

            $this.tab('show');
        }
    </script>
@stop
