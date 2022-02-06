<li>
    <a href="#" class="clearfix" data-notif-id="{{ $notif->id }}">
        <div class="image">
            <i class="fa fa-user bg-success text-light" aria-hidden="true"></i>
        </div>
        <span class="title">{{ trans('general/admin_lang.notif_new_user') }}</span>
        <span class="message">{{ $notif->data['name'] }}</span>
    </a>
</li
{{-- 
<li>
    <a href="#" class="clearfix">
        <div class="image">
            <i class="fas fa-thumbs-down bg-danger text-light"></i>
        </div>
        <span class="title">Server is Down!</span>
        <span class="message">Just now</span>
    </a>
</li>
<li>
    <a href="#" class="clearfix">
        <div class="image">
            <i class="bx bx-lock bg-warning text-light"></i>
        </div>
        <span class="title">User Locked</span>
        <span class="message">15 minutes ago</span>
    </a>
</li>
<li>
    <a href="#" class="clearfix">
        <div class="image">
            <i class="fas fa-signal bg-success text-light"></i>
        </div>
        <span class="title">Connection Restaured</span>
        <span class="message">10/10/2021</span>
    </a>
</li> --}}
