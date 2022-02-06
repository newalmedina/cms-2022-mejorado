
{{-- Notificaciones --}}
@if(!auth()->guest())
    @php
        $notifications = auth()->user()->unreadNotifications()->where(function($q) {
              $q->where('visibility', 'admin')
              ->orWhere('visibility', '=', '');
          })->get();
    @endphp

    @if($notifications->count()>0 )
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell" aria-hidden="true"></i>
                <span class="badge badge-warning navbar-badge">{{ $notifications->count() }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">{{ trans('general/admin_lang.notif_count', ['contador' => $notifications->count()]) }}</span>

                @foreach ($notifications as $notif)
                    @if(View::exists('admin.includes.notifications.'.Str::snake(class_basename($notif->type))))
                        @include('admin.includes.notifications.'.Str::snake(class_basename($notif->type)))
                    @endif
                @endforeach

                <div class="dropdown-divider"></div>
                <a href="#" id="mark-all" class="dropdown-item dropdown-footer">{{ trans(('general/admin_lang.notif_mark_all')) }}</a>
            </div>
        </li>
        <script>
            var notifications = document.querySelectorAll('[data-notif-id]');
            var markNotification = function() {
                document.getElementById('notify_id').value = this.getAttribute("data-notif-id");
                document.getElementById('notification-form').action = '{{ url('admin/notification/mark') }}';
                document.getElementById('notification-form').submit();
            };

            var markAllNotifications = function() {
                document.getElementById('notification-form').action = '{{ url('admin/notification/mark_all') }}';
                document.getElementById('notification-form').submit();
            };

            for (var i = 0; i < notifications.length; i++) {
                notifications[i].addEventListener('click', markNotification, false);
            }
            document.getElementById('mark-all').addEventListener('click', markAllNotifications, false);


        </script>
        <form id="notification-form" action="" method="POST" style="display: none;">
            {{ csrf_field() }}
            <input type="hidden" id="notify_id" name="notify_id" value="">
        </form>
    @endif
@endif
