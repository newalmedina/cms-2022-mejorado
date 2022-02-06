<?php

namespace Tests\Feature\Auth;

use Tests\ClavelBaseTestCase;

class AuthenticationTest extends ClavelBaseTestCase
{
    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = $this->getAdminUser();

        $response = $this->post('/admin/login', [
            'login' => $user->username,
            'password' => 'password'
        ]);

        $response->assertStatus(302);

        $this->assertAuthenticated();

        $response->assertRedirect('/admin');
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = $this->getAdminUser();

        $this->post('/admin/login', [
            'login' => $user->username,
            'password' => 'wrong-password'
        ]);


        $this->assertGuest();
    }
}
