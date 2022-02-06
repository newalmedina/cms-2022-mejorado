<?php

namespace Tests;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClavelBaseTestCase extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $locale;

    public function setUp(): void
    {
        parent::setUp();

        $this->locale = config('app.locale');

        // seed the database
        $this->artisan('db:seed');
        // alternatively you can call
        // $this->seed()

        // Artisan::call('migrate');
        // Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);


    }

    protected function getAdminUser($values = [])
    {
        // Formzamos una serie de valores por defecto
        $vars = [
            'active' => true,
            'confirmed' => true
        ];

        // Si queremos cambiar o modificar algunos de los valores por defecto lo pasaremos por parametor
        // Ejemplo:
        // [  'email_verified_at' => null ]
        if(!empty($values)) {
            $vars = array_replace($vars, $values);
        }

        // Creamos el usuario y le asignamos los valores por defecto establecidos
        $user = User::factory()->create($vars);
        $roles = Role::select('id')->where("name", "admin")->first()->toArray();

        $user->roles()->sync([]);
        $user->roles()->attach($roles);

        return $user;
    }

}
