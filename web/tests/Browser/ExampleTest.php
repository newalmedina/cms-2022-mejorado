<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Usuario');
        });


    }


    public function testLoginIncorrect()
    {
        $this->browse(function ($browser) {
            $browser->visit('/admin/login')
                    ->type('login','admin')
                    ->type('password', 'admin3')
                    ->press('Acceder')
                    ->assertPathIs('/admin/login');
        });
    }


    public function testLogin()
    {
        $this->browse(function ($browser) {
            $browser->visit('/admin/login')
                    ->type('login','admin')
                    ->type('password', 'admin')
                    ->press('Acceder')
                    ->assertPathIs('/admin');
        });
    }



}
