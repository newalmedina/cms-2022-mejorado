@if (session()->has('original-user-suplantar'))

    <div class="alert bg-orange mt-2 mb-0">
        <i class="fa fa-user-secret" aria-hidden="true"></i>
        <a class="text-decoration-none" href="{{ url('admin/users/suplantar/revertir') }}">
            Revertir al administrador
        </a>
    </div>

@endif
