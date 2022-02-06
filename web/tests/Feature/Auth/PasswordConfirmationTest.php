<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Tests\ClavelBaseTestCase;

class PasswordConfirmationTest extends ClavelBaseTestCase
{

    public function test_confirm_password_screen_can_be_rendered()
    {
        $user = $this->getAdminUser();

        $response = $this->actingAs($user)->get('/admin/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        $user = $this->getAdminUser();

        $response = $this->actingAs($user)->post('/admin/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $user = $this->getAdminUser();

        $response = $this->actingAs($user)->post('/admin/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
