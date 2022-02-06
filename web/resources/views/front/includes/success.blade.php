@if (Session::get('success',"") != "")
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ date('d/m/Y H:i:s') }}</strong>
        {{ Session::get('success',"") }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true" aria-label="Close"></button>
    </div>
@endif

@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div>
            <strong>{{ date('d/m/Y H:i:s') }}</strong>
            {{ session('status') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
