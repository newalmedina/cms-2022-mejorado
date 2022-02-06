@if(session()->has("original-user-suplantar"))
<div class="header-top" style="height: 36px;">
    <div class="container">
        <div class="header-row py-2">
            <div class="header-column justify-content-start">
                <div class="header-row">
                    <nav class="header-nav-top">
                        <ul class="nav nav-pills">
                            <li
                                class="nav-item nav-item-left-border nav-item-left-border-remove nav-item-left-border-sm-show">
                                <span class="ws-nowrap"><i class="fa fa-user-secret" aria-hidden="true"></i>
                                    <a class="text-decoration-none" href="{{ url("admin/users/suplantar/revertir") }}">
                                        Revertir al administrador
                                    </a>
                                </span>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

        </div>
    </div>
</div>
@endif
