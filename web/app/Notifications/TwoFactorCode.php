<?php

namespace App\Notifications;

use App\Models\UserTwoFactor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TwoFactorCode extends Notification
{
    use Queueable;

    private $userTwoFactor = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UserTwoFactor $userTwoFactor)
    {
        $this->userTwoFactor = $userTwoFactor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->line('Your two factor code is '.$this->userTwoFactor->two_factor_code)
            ->action('Verify Here', route('admin.2fa.verify.index'))
            ->line('The code will expire in 10 minutes')
            ->line('If you have not tried to login, ignore this message.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
