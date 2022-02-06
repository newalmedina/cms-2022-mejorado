<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\ClavelBaseTestCase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminResetPasswordNotification;

class PasswordResetTest extends ClavelBaseTestCase
{

    public function test_reset_password_link_screen_can_be_rendered()
    {
        $response = $this->get('/admin/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested()
    {
        Notification::fake();

        $user = $this->getAdminUser();

        $response = $this->post('/admin/forgot-password', ['login' => $user->email]);
        $response->assertStatus(302);
        $response->assertRedirect('/');

        Notification::assertSentTo($user, AdminResetPasswordNotification::class);
    }

    public function test_reset_password_screen_can_be_rendered()
    {
        Notification::fake();

        $user = $this->getAdminUser();

        $this->post('/admin/forgot-password', ['login' => $user->email]);

        Notification::assertSentTo($user, AdminResetPasswordNotification::class, function ($notification) {
            $response = $this->get('/admin/reset-password/'.$notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token()
    {
        Notification::fake();

        $user = $this->getAdminUser();

        $this->post('/admin/forgot-password', ['login' => $user->email]);

        Notification::assertSentTo($user, AdminResetPasswordNotification::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
