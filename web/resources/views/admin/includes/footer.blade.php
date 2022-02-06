<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
        <b>{{ trans("general/admin_lang.version") }}</b> {{ config('general.app_version') }}
    </div>
    <!-- Default to the left -->
    <strong>{{ trans("general/admin_lang.copy") }} &copy; {{ date("Y") }} <a href="http://www.aduxia.com" target="_blank">Aduxia</a>.</strong> {{ trans("general/admin_lang.rights") }}

    @include('admin.includes.suplantacion')
</footer>
