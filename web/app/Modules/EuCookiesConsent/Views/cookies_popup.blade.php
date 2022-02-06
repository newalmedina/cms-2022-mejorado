<div id="modal_cookies" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirmCookiesModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="confirmCookiesModalLabel">
                    {{ trans('EuCookiesConsent::lang.cookies_modal_title') }}
                </h4>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="confirmCookiesModalBody" class="modal-body">
                {{ Form::open(['id' => 'formEuCookiesConsent', 'url' => config('eu-cookie-consent.route')]) }}
                {!! Form::hidden("reject_all", 0, array('id' => 'reject_all')) !!}
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">Configuración de cookies</a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" href="#profile" role="tab"
                            aria-controls="profile" aria-selected="false">
                            Declaración de cookies</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">


                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-12 mt-2">
                                    <small>{!! trans('EuCookiesConsent::lang.cookies_modal_aviso') !!}</small>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-12 mt-2 mb-4">
                                    <button id="set_all_cookies_popup" type="button" class="btn btn-primary btn-sm">{{ trans('EuCookiesConsent::lang.cookies_allow_all') }}</button>
                                    <button id="unset_all_cookies_popup" type="button" class="btn btn-primary btn-sm">{{ trans('EuCookiesConsent::lang.cookies_deny_all') }}</button>
                                </div>
                            </div>



                            <div class="row mb-2">
                                <div class="col-sm-1">
                                    <a href="#">
                                        <label class="switch mt-2">
                                            <input type="checkbox" name="cookies-necessary" id="cookies-necessary" value="1" checked disabled>
                                            <span class="slider round"></span>
                                        </label>
                                    </a>
                                </div>
                                <div class="col-sm-11">
                                    <b>{{ trans('EuCookiesConsent::lang.cookies_necessary') }}</b><br>
                                    <small>{{ trans('EuCookiesConsent::lang.cookies_necessary_desc') }}</small>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-1">
                                    <a href="#">
                                        <label class="switch mt-2">
                                            <input type="checkbox" name="cookies-preferences" id="cookies-preferences" value="1" checked>
                                            <span class="slider round"></span>
                                        </label>
                                    </a>
                                </div>
                                <div class="col-sm-11">
                                    <b>{{ trans('EuCookiesConsent::lang.cookies_preferences') }}</b><br>
                                    <small>{{ trans('EuCookiesConsent::lang.cookies_preferences_desc') }}</small>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-1">
                                    <a href="#">
                                        <label class="switch mt-2">
                                            <input type="checkbox" name="cookies-analytical" id="cookies-analytical" value="1">
                                            <span class="slider round"></span>
                                        </label>
                                    </a>
                                </div>
                                <div class="col-sm-11">
                                    <b>{{ trans('EuCookiesConsent::lang.cookies_analytical') }}</b><br>
                                    <small>{{ trans('EuCookiesConsent::lang.cookies_analytical_desc') }}</small>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-1">
                                    <a href="#">
                                        <label class="switch mt-2">
                                            <input type="checkbox" name="cookies-marketing" id="cookies-marketing" value="1">
                                            <span class="slider round"></span>
                                        </label>
                                    </a>
                                </div>
                                <div class="col-sm-11">
                                    <b>{{ trans('EuCookiesConsent::lang.cookies_marketing') }}</b><br>
                                    <small>{{ trans('EuCookiesConsent::lang.cookies_marketing_desc') }}</small>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-1">
                                    <a href="#">
                                        <label class="switch mt-2">
                                            <input type="checkbox" name="cookies-other" id="cookies-other" value="1">
                                            <span class="slider round"></span>
                                        </label>
                                    </a>
                                </div>
                                <div class="col-sm-11">
                                    <b>{{ trans('EuCookiesConsent::lang.cookies_other') }}</b><br>
                                    <small>{{ trans('EuCookiesConsent::lang.cookies_other_desc') }}</small>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-12 mt-5">
                                    <h5>{{ trans('EuCookiesConsent::lang.cookies_modal_more_info_title') }}</h5>
                                </div>

                                <div class="col-sm-12">
                                    <small>{!! trans('EuCookiesConsent::lang.cookies_modal_more_info', ['politica_cookies' => url('/')]) !!}</small>
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                        <div class="row">
                            <div class="col-sm-12 mt-2 mb-4">
                                <small>{!! trans('EuCookiesConsent::lang.cookies_modal_aviso') !!}</small>
                            </div>
                        </div>

                        <div class="row">

                            @include('EuCookiesConsent::cookies_types')

                        </div>

                    </div>

                    {{ Form::close() }}
                </div>
                <div id="confirmCookiesModalFooter" class="modal-footer">
                    <button id="accept_all_cookies_popup" type="button" class="btn btn-primary">{{ trans('EuCookiesConsent::lang.cookies_save_settings') }}</button>
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ trans('EuCookiesConsent::lang.cookies_cancel') }}</button>
                </div>

            </div>
        </div>
    </div>
</div>
