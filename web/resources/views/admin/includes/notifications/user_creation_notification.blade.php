<div class="dropdown-divider"></div>

    <a href="#" class="dropdown-item" data-notif-id="{{ $notif->id }}">
        <i class="fas fa-user text-success mr-2" aria-hidden="true"></i>
        {{ trans('general/admin_lang.notif_new_user') }}:<br> {{ $notif->data['name'] }}
    </a>

