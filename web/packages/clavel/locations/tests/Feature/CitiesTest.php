<?php

namespace Clavel\Locations\Tests\Feature;

use Tests\AdminTestCase;
use Clavel\Locations\Models\City;
use Database\Seeders\CitiesPermissionSeeder;
use Illuminate\Database\Eloquent\SoftDeletes;


// Para llamar directamente a esta prueba
// ./vendor/bin/phpunit app/Modules/Cities/tests/Feature/CitiesTest.php
//  ./vendor/bin/phpunit --filter CitiesTest

class CitiesTest extends AdminTestCase
{
    private $requiredFields = [
        "active",
        "country_id",
        "province_id",
        "ccaa_id",
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
            CitiesPermissionSeeder::class,
        ]);

        // Asignamos permisos al usuario autenticado
        // para que pueda ejecutar las acciones.
        $this->user->syncPermissions([
            'admin-cities-list',
            'admin-cities-create',
            'admin-cities-update',
            'admin-cities-delete',
            'admin-cities-read',
        ]);
    }

    private function createObject()
    {
        return \Clavel\Locations\Models\City::factory()->create();
    }

    private function makeObject()
    {
        return \Clavel\Locations\Models\City::factory()->make();
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
        $model->active = !$model->active;

        return $model;
    }

    private function getResource($model, $with_lang = false)
    {
        // Devolvemos el modelo como un json controlado. El toArray() no sirve porque tenemos los
        // los parametros de POST diferente y tiene que ser en el formato del POST
        $data = [];
        foreach ($this->requiredFields as $key) {
            $data[$key] = $model->{$key};
        }


        if ($with_lang && !empty($this->requiredFieldsLang)) {
            $data["lang"][$this->locale] = [];

            $data["lang"][$this->locale]["id"] = empty($model->translate('es')->id) ? null : $model->translate('es')->id;
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
        $response = $this->get(route('cities.index'));

        // La respuesta debe ser 200 o 302
        // Si solo hay admin hay una redirección por lo que la respuesta es 302
        $this->assertContains($response->getStatusCode(), array(200, 302));

        // Miramos si la vista es la correcta
        $response->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_leer_cities()
    {
        // Tenemos al menos un objeto en la base de datos
        $city = $this->createObject();

        // El usuario visita el listado de objetos
        $response = $this->actingAs($this->user)->post('admin/cities/list');

        // Veremos en el listado el objeto creado
        $response->assertSee($city->country_id);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_crear_un_cities()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto pero no lo insertamos
        $city = $this->makeObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $this->post('admin/cities', $this->getResource($city, true))
            ->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_crear_un_cities()
    {
        // Creamos un objeto pero no lo insertamos
        $city = $this->makeObject();

        //  El usuario crea el objeto
        $this->actingAs($this->user)
            ->post('admin/cities', $this->getResource($city, true))
            ->assertSessionDoesntHaveErrors();


        // Leemos el numero de objetos en la base de datos
        $this->assertEquals(1, City::all()->count());

        // Verificamos que en la base de datos hay un registro con los datos creados
        $this->assertDatabaseHas('cities', $this->getResource($city));
    }

    /** @test */
    public function usuario_no_puede_crear_un_cities_datos_incompletos()
    {
        // Creamos un objeto con datos incompletos
        $city = new City();

        //  El usuario crea el objeto pero le dice que faltan los required
        $this->actingAs($this->user)->post('admin/cities', $this->getResource($city, true))
            ->assertSessionHasErrors($this->requiredFields);
    }

    /** @test */
    public function usuario_puede_editar_un_cities()
    {
        // Tenemos al menos un objeto en la base de datos
        $city = $this->createObject();

        // El usuario edita el objeto
        $response = $this->actingAs($this->user)->get('admin/cities/' . $city->id . "/edit");

        // Vemos que se ven los datos
        $response->assertSee($city->active);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_editar_un_cities()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $city = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $response = $this->put('/admin/cities/' . $city->id, $this->getResource($city, true))
            ->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_modificar_un_cities()
    {
        // Creamos un objeto
        $city = $this->createObject();

        // Modificamos algunos datos
        $city = $this->modifyData($city);

        //  El usuario crea el objeto
        $this->actingAs($this->user)->put('/admin/cities/' . $city->id, $this->getResource($city, true));

        // Verificamos que en la base de datos hay un registro con los datos modificados
        $this->assertDatabaseHas(
            'cities',
            $this->getResource($city)
        );
    }

    /** @test */
    public function usuario_no_puede_modificar_un_cities_datos_incompletos()
    {
        // Creamos un objeto
        $city = $this->createObject();

        // Modificamos algunos datos
        $city = $this->cleanData($city);

        //  El usuario crea el objeto pero le dice que faltan los required
        $response = $this->actingAs($this->user)->put('/admin/cities/' . $city->id, $this->getResource($city, true));

        $response->assertSessionHasErrors($this->requiredFields);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_borrar_un_cities()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $city = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $response =  $this->delete('/admin/cities/' . $city->id)
            ->assertRedirect('/admin/login');

        // Verificamos que en la base de datos sigue estando el registro
        if (in_array(SoftDeletes::class, class_uses(get_class($city)))) {
            $this->assertDatabaseHas('cities', [
                'id' => $city->id,
                'deleted_at' => null,
            ]);
        } else {
            $this->assertDatabaseHas('cities', ['id' => $city->id]);
        }
    }

    /** @test */
    public function usuario_puede_borrar_un_cities()
    {
        // Creamos un objeto
        $city = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $this->actingAs($this->user)->delete('/admin/cities/' . $city->id);

        // Verificamos que en la base de datos no esta el registro
        if (in_array(SoftDeletes::class, class_uses(get_class($city)))) {
            $this->assertDatabaseHas('cities', [
                'id' => $city->id,
                'deleted_at' => now(),
            ]);
        } else {
            $this->assertDatabaseMissing('cities', ['id' => $city->id]);
        }
    }
}
