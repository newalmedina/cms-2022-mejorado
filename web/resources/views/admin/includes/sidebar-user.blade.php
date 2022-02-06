<!-- Sidebar user panel (optional) -->
<div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        @if(!empty(Auth::user()->userProfile->photo))
            <img src="{{ url('admin/profile/getphoto/'.Auth::user()->userProfile->photo) }}"
                class="img-circle elevation-2" alt="{{ Auth::user()->userProfile->fullname }}">
        @else
            <img src="{{ asset("/assets/admin/img/user.png") }}"
                class="img-circle elevation-2" alt="{{ Auth::user()->userProfile->fullname }}"/>
        @endif
    </div>
    <div class="info">
        <a href="{{ url('admin/profile') }}" class="d-block">
            {{ Auth::user()->userProfile->fullname }}
        </a>
        <a href="{{ url('admin/profile') }}">
            @if(!empty(Auth::user()->online()))
                <i class="far fa-circle text-success"></i>
            @else
                <i class="far fa-circle text-danger"></i>
            @endif
            Online
        </a>
    </div>
</div>
