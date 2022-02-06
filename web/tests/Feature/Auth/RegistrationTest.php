<?php

namespace Tests\Feature\Auth;

use Tests\ClavelBaseTestCase;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;

class RegistrationTest extends ClavelBaseTestCase
{

    public function test_registration_screen_can_be_rendered()
    {
        if(Route::has('register')) {
            $response = $this->get('/admin/register');

            $response->assertStatus(200);
        } else {
            $this->assertTrue(true);
        }
    }

    //  ./vendor/bin/phpunit --filter methodName
    public function test_new_users_can_register()
    {
        if(Route::has('register')) {
            $response = $this->post('/admin/register', [
                'name' => 'Test User',
                'username' => 'clavel_user',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'terms' => 'on',
            ]);

            $this->assertAuthenticated();
            $response->assertRedirect('/admin');
        } else {
            $this->assertTrue(true);
        }
    }
}
