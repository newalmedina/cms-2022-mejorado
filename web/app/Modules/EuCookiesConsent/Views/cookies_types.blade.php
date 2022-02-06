<div class="col-sm-12">
    <b><strong>Cookies necesarias</strong></b><br>
    <small>Algunas cookies son necesarias para proporcionar una funcionalidad básica. El
        sitio web no
        funcionará correctamente sin estas cookies y están habilitadas de forma
        predeterminada y
        no se pueden deshabilitar.</small>
    <table class="table table-striped mt-3">
        <thead class="thead-light">
            <tr>
                <th>Nombre</th>
                <th>Hostname</th>
                <th>Path</th>
                <th>Expiración</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @include('EuCookiesConsent::cookies_types_necessary')
            {{-- @include('EuCookiesConsent::cookies_types_nothing') --}}
        </tbody>
    </table>
</div>
<div class="col-sm-12">
    <b><strong>Preferencias</strong></b><br>
    <small>Las cookies de preferencia permiten que el sitio web recuerde información para
        personalizar cómo
        el sitio web se ve o se comporta para cada usuario. Esto puede incluir el
        almacenamiento de
        moneda, región, idioma o tema de color seleccionados por el usuario.</small>
    <table class="table table-striped mt-3">
        <thead class="thead-light">
            <tr>
                <th>Nombre</th>
                <th>Hostname</th>
                <th>Path</th>
                <th>Expiración</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @include('EuCookiesConsent::cookies_types_preferences')
            {{-- @include('EuCookiesConsent::cookies_types_nothing') --}}
        </tbody>
    </table>
</div>
<div class="col-sm-12">
    <b><strong>Cookies analíticas</strong></b><br>
    <small>Las cookies analíticas nos ayudan a mejorar nuestro sitio web al recopilar e
        informar
        información sobre su uso.</small>
    <table class="table table-striped mt-3">
        <thead class="thead-light">
            <tr>
                <th>Nombre</th>
                <th>Hostname</th>
                <th>Path</th>
                <th>Expiración</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            {{-- @include('EuCookiesConsent::cookies_types_analytical') --}}
            @include('EuCookiesConsent::cookies_types_nothing')

        </tbody>
    </table>
</div>
<div class="col-sm-12">
    <b><strong>Cookies de marketing</strong></b><br>
    <small>Las cookies de marketing se utilizan para rastrear a los visitantes a través de
        sitios web para permitir a los editores
        para mostrar anuncios relevantes y atractivos.</small>
    <table class="table table-striped mt-3">
        <thead class="thead-light">

            <tr>
                <th>Nombre</th>
                <th>Hostname</th>
                <th>Path</th>
                <th>Expiración</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            {{-- @include('EuCookiesConsent::cookies_types_marketing') --}}
            @include('EuCookiesConsent::cookies_types_nothing')
        </tbody>
    </table>
</div>
<div class="col-sm-12">
    <b><strong>Otras cookies</strong></b><br>
    <small>Las cookies de esta categoría aún no se han categorizado y el propósito puede ser
        desconocido en este momento.</small>
    <table class="table table-striped mt-3">
        <thead class="thead-light">
            <tr>
                <th>Nombre</th>
                <th>Hostname</th>
                <th>Path</th>
                <th>Expiración</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @include('EuCookiesConsent::cookies_types_nothing')
            {{-- @include('EuCookiesConsent::cookies_types_other') --}}

        </tbody>
    </table>
</div>
