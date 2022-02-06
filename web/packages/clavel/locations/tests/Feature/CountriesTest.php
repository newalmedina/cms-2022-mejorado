<?php

namespace Clavel\Locations\Tests\Feature;

use Tests\AdminTestCase;
use Illuminate\Support\Facades\DB;
use Clavel\Locations\Models\Country;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Seeders\CountriesPermissionSeeder;

// Para llamar directamente a esta prueba
// ./vendor/bin/phpunit app/Modules/Countries/tests/Feature/CountriesTest.php
// ./vendor/bin/phpunit --filter CountriesTest
// ./vendor/bin/phpunit --filter usuario_puede_crear_un_Countries
// php artisan test --filter \App\Modules\Countries\Tests\Feature\CountriesTest
// php artisan test --filter CountriesTest
// php artisan test --filter usuario_puede_crear_un_Countries
// php artisan test --filter CountriesTest::usuario_puede_crear_un_Countries

// <testsuite name="Feature">
// ...
// <directory suffix="Test.php">./app/Modules/Countries/tests/Feature</directory>
// </testsuite>

class CountriesTest extends AdminTestCase
{
    private $requiredFields = [
         "active",
"alpha2_code",
"alpha3_code",
"numeric_code"
    ];

    private $requiredFieldsLang = [
         "name"
    ];


    public function setUp(): void
    {
        parent::setUp();

        // Ejecutamos los seeders adicionales para
        // este grupo de tests.
        $this->seed([
            CountriesPermissionSeeder::class,
        ]);

        // Asignamos permisos al usuario autenticado
        // para que pueda ejecutar las acciones.
        $this->user->syncPermissions([
            'admin-countries-list',
            'admin-countries-create',
            'admin-countries-update',
            'admin-countries-delete',
            'admin-countries-read',
        ]);
    }

    private function createObject()
    {
        return \Clavel\Locations\Models\Country::factory()->create();
    }

    private function makeObject()
    {
        return \Clavel\Locations\Models\Country::factory()->make();
    }

    private function cleanData($model)
    {
        foreach ($this->requiredFields as $key) {
            $model->{$key} = "";
        }

        return $model;
    }
    private function modifyData($model)
    {
        $model->numeric_code = $this->faker->numberBetween(1, 999);

        return $model;
    }

    private function getResource($model, $with_lang=false)
    {
        // Devolvemos el modelo como un json controlado. El toArray() no sirve porque tenemos los
        // los parametros de POST diferente y tiene que ser en el formato del POST
        $data = [];
        foreach ($this->requiredFields as $key) {
            $data[$key] = $model->{$key};
        }


        if ($with_lang && !empty($this->requiredFieldsLang)) {
            $data["lang"][$this->locale] = [];

            $data["lang"][$this->locale]["id"] = empty($model->translate('es')->id)?null:$model->translate('es')->id;
            foreach ($this->requiredFieldsLang as $key) {
                $data["lang"][$this->locale][$key] = $model->{$key};
            }
        }

        return $data;
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_acceder()
    {
        // Debo ser invitado
        $this->assertGuest();

        // Llamamos a la ruta de listado
        $response = $this->get(route('countries.index'));

        // La respuesta debe ser 200 o 302
        // Si solo hay admin hay una redirección por lo que la respuesta es 302
        $this->assertContains($response->getStatusCode(), array(200,302));

        // Miramos si la vista es la correcta
        $response->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_leer_countries()
    {
        // Tenemos al menos un objeto en la base de datos
        $country = $this->createObject();

        // El usuario visita el listado de objetos
        $response = $this->actingAs($this->user)->post('admin/countries/list');

        // Veremos en el listado el objeto creado
        $response->assertSee($country->numeric_code);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_crear_un_country()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto pero no lo insertamos
        $country = $this->makeObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $this->post('admin/countries', $this->getResource($country, true))
            ->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_crear_un_country()
    {
        // Creamos un objeto pero no lo insertamos
        $country = $this->makeObject();

        //  El usuario crea el objeto
        $this->actingAs($this->user)
            ->post('admin/countries', $this->getResource($country, true))
            ->assertSessionDoesntHaveErrors();


        // Leemos el numero de objetos en la base de datos
        $this->assertEquals(1, Country::all()->count());

        // Verificamos que en la base de datos hay un registro con los datos creados
        $this->assertDatabaseHas('countries', $this->getResource($country));
    }

    /** @test */
    public function usuario_no_puede_crear_un_country_datos_incompletos()
    {
        // Creamos un objeto con datos incompletos
        $country = new Country();

        //  El usuario crea el objeto pero le dice que faltan los required
        $this->actingAs($this->user)->post('admin/countries', $this->getResource($country, true))
            ->assertSessionHasErrors($this->requiredFields);
    }

    /** @test */
    public function usuario_puede_editar_un_country()
    {
        // Tenemos al menos un objeto en la base de datos
        $country = $this->createObject();

        // El usuario edita el objeto
        $response = $this->actingAs($this->user)->get('admin/countries/'.$country->id."/edit");

        // Vemos que se ven los datos
        $response->assertSee($country->numeric_code);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_editar_un_country()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $country = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $response = $this->put('/admin/countries/'.$country->id, $this->getResource($country, true))
            ->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_modificar_un_country()
    {
        // Creamos un objeto
        $country = $this->createObject();

        // Modificamos algunos datos
        $country = $this->modifyData($country);

        //  El usuario crea el objeto
        $this->actingAs($this->user)->put('/admin/countries/'.$country->id, $this->getResource($country, true));

        // Verificamos que en la base de datos hay un registro con los datos modificados
        $this->assertDatabaseHas(
            'countries',
            $this->getResource($country)
        );
    }

    /** @test */
    public function usuario_no_puede_modificar_un_country_datos_incompletos()
    {
        // Creamos un objeto
        $country = $this->createObject();

        // Modificamos algunos datos
        $country = $this->cleanData($country);

        //  El usuario crea el objeto pero le dice que faltan los required
        $response = $this->actingAs($this->user)->put('/admin/countries/'.$country->id, $this->getResource($country, true));

        $response->assertSessionHasErrors($this->requiredFields);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_borrar_un_country()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $country = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $response =  $this->delete('/admin/countries/'.$country->id)
            ->assertRedirect('/admin/login');

        // Verificamos que en la base de datos sigue estando el registro
        if (in_array(SoftDeletes::class, class_uses(get_class($country)))) {
            $this->assertDatabaseHas('countries', [
                'id' => $country->id,
                'deleted_at' => null,
            ]);
        } else {
            $this->assertDatabaseHas('countries', ['id'=> $country->id]);
        }
    }

    /** @test */
    public function usuario_puede_borrar_un_country()
    {
        // Creamos un objeto
        $country = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $this->actingAs($this->user)->delete('/admin/countries/'.$country->id) ;

        // Verificamos que en la base de datos no esta el registro
        if (in_array(SoftDeletes::class, class_uses(get_class($country)))) {
            $this->assertDatabaseHas('countries', [
                'id' => $country->id,
                'deleted_at' => now(),
            ]);
        } else {
            $this->assertDatabaseMissing('countries', ['id'=> $country->id]);
        }
    }
}
