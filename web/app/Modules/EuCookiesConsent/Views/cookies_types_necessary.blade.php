<tr>
    <td>{{ config('session.cookie') }} (Session Cookie)</td>
    <td>{{ url('/') }}</td>
    <td>/</td>
    <td>{{ config('session.lifetime') }} minutos</td>
    <td></td>
</tr>
<tr>
    <td colspan="5">
        <small>El servidor utiliza una cookie para identificar las sesiones de los usuarios. Sin esta cookie, el sitio web no funciona.</small>
    </td>
</tr>
<tr>
    <td>XSRF-Token Cookie</td>
    <td>{{ url('/') }}</td>
    <td>/</td>
    <td>{{ config('session.lifetime') }} minutos</td>
    <td></td>
</tr>
<tr>
    <td colspan="5">
        <small>El servidor genera automáticamente un "token" CSRF para cada sesión de usuario activa administrada por la aplicación. Este token se utiliza para verificar que el usuario autenticado es el que realmente realiza las solicitudes a la aplicación.</small>
    </td>
</tr>

