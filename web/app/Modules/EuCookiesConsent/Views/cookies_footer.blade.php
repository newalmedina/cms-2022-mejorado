<style>


    .politica_de_cookies {
        background-color: #212327;
        color: #ecf0f1;
        position: fixed;
        bottom: 0;
        left: 0;
        font-size: 14px;
        padding: 10px;
        width: 100%;
        z-index: 9999;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 35px;
        height: 18px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgb(207, 67, 67);
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 2px;
        bottom: 1px;
        background-color: white;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #54c55e;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #54c55e;
    }

    input:checked+.slider:before {
        transform: translateX(16px);
    }


    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

</style>

<!-- COOKIES -->
@include('EuCookiesConsent::cookies_popup')

<div class="politica_de_cookies text-left">

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                {!! trans('EuCookiesConsent::lang.cookies_info', ['politica_cookies' => url('/')]) !!}
            </div>
            <div class="col-md-4 text-center">
                <a class="btn btn-primary" href="#" id="accept_all_cookies">{{ trans('EuCookiesConsent::lang.cookies_accept_all') }}</a>
                <a class="btn btn-danger" href="#" id="reject_all_cookies">{{ trans('EuCookiesConsent::lang.cookies_reject_all') }}</a>
                <a class="btn btn-warning" href="#" id="config_cookies" data-bs-toggle="modal" data-bs-target="#modal_cookies">{{ trans('EuCookiesConsent::lang.cookies_settings') }}</a>
            </div>
            <br clear="all">
        </div>
    </div>
</div>

<!-- /COOKIES -->


<script>

$(document).ready(function() {
    // Cookies


    $("#accept_all_cookies, #accept_all_cookies_popup").click(function(event) {
        event.preventDefault();
        $('#formEuCookiesConsent').submit();
    });

    $("#reject_all_cookies").click(function(event) {
        event.preventDefault();
        $('#reject_all').val(1);
        $('#formEuCookiesConsent').submit();
    });

    $("#config_cookies").click(function() {

        $('#modal_cookies').modal({
            backdrop: 'static',
            keyboard: false
        });

    });


    $("#set_all_cookies_popup").click(function(event) {
        $("#cookies-preferences").prop("checked", true);
        $("#cookies-analytical").prop("checked", true);
        $("#cookies-marketing").prop("checked", true);
        $("#cookies-other").prop("checked", true);
    });

    $("#unset_all_cookies_popup").click(function(event) {
        $("#cookies-preferences").prop("checked", false);
        $("#cookies-analytical").prop("checked", false);
        $("#cookies-marketing").prop("checked", false);
        $("#cookies-other").prop("checked", false);
    });


});

</script>
