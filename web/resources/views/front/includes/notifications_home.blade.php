{{-- Notificaciones --}}
@if(!auth()->guest())
    @php
        $notifications = auth()->user()->unreadNotifications()->where(function($q) {
              $q->where('visibility', 'front')
              ->orWhere('visibility', '=', '');
          })->get();
    @endphp
    @if($notifications->count()>0 )


        <ul class="notifications" id="mainNotifications">


            <li id="notify-container">
                <a href="#" class="dropdown-toggle notification-icon show" data-bs-toggle="dropdown" aria-expanded="true">
                    <i class="bx bx-bell"></i>
                    <span class="badge">{{ $notifications->count() }}</span>
                </a>

                <div class="dropdown-menu notification-menu show" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(0px, 32px, 0px);">
                    <div class="notification-title">
                        <span class="float-end badge badge-default">{{ $notifications->count() }}</span>
                        {{ trans('general/admin_lang.notif_count', ['contador' => $notifications->count()]) }}
                    </div>

                    <div class="content">
                        <ul>
                            @foreach ($notifications as $notif)
                                @if(View::exists('front.includes.notifications.'.Str::snake(class_basename($notif->type))))
                                    @include('front.includes.notifications.'.Str::snake(class_basename($notif->type)))
                                @endif
                            @endforeach
                        </ul>
                        <hr>

                        <div class="text-end">
                            <a id="mark-all" href="#" class="view-more">{{ trans(('general/admin_lang.notif_mark_all')) }}</a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <script>
            var notifications = document.querySelectorAll('[data-notif-id]');
            var markNotification = function() {
                document.getElementById('notify_id').value = this.getAttribute("data-notif-id");
                document.getElementById('notification-form').action = '{{ url('notification/mark') }}';
                document.getElementById('notification-form').submit();
            };

            var markAllNotifications = function() {
                document.getElementById('notification-form').action = '{{ url('notification/mark_all') }}';
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

