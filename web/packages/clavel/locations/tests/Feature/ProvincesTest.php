<?php

namespace Clavel\Locations\Tests\Feature;

use Tests\AdminTestCase;
use Illuminate\Support\Facades\DB;
use Clavel\Locations\Models\Province;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Seeders\ProvincesPermissionSeeder;

// Para llamar directamente a esta prueba
// ./vendor/bin/phpunit app/Modules/Provinces/tests/Feature/ProvincesTest.php
// ./vendor/bin/phpunit --filter ProvincesTest
// ./vendor/bin/phpunit --filter usuario_puede_crear_un_Provinces
// php artisan test --filter \App\Modules\Provinces\Tests\Feature\ProvincesTest
// php artisan test --filter ProvincesTest
// php artisan test --filter usuario_puede_crear_un_Provinces
// php artisan test --filter ProvincesTest::usuario_puede_crear_un_Provinces

// <testsuite name="Feature">
// ...
// <directory suffix="Test.php">./app/Modules/Provinces/tests/Feature</directory>
// </testsuite>

class ProvincesTest extends AdminTestCase
{
    private $requiredFields = [
         "active",
         "country_id"
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
            ProvincesPermissionSeeder::class,
        ]);

        // Asignamos permisos al usuario autenticado
        // para que pueda ejecutar las acciones.
        $this->user->syncPermissions([
            'admin-provinces-list',
            'admin-provinces-create',
            'admin-provinces-update',
            'admin-provinces-delete',
            'admin-provinces-read',
        ]);
    }

    private function createObject()
    {
        return \Clavel\Locations\Models\Province::factory()->create();
    }

    private function makeObject()
    {
        return \Clavel\Locations\Models\Province::factory()->make();
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
        $response = $this->get(route('provinces.index'));

        // La respuesta debe ser 200 o 302
        // Si solo hay admin hay una redirección por lo que la respuesta es 302
        $this->assertContains($response->getStatusCode(), array(200,302));

        // Miramos si la vista es la correcta
        $response->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_leer_provinces()
    {
        // Tenemos al menos un objeto en la base de datos
        $province = $this->createObject();

        // El usuario visita el listado de objetos
        $response = $this->actingAs($this->user)->post('admin/provinces/list');

        // Veremos en el listado el objeto creado
        $response->assertSee($province->country_id);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_crear_un_province()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto pero no lo insertamos
        $province = $this->makeObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $this->post('admin/provinces', $this->getResource($province, true))
            ->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_crear_un_province()
    {
        // Creamos un objeto pero no lo insertamos
        $province = $this->makeObject();

        //  El usuario crea el objeto
        $this->actingAs($this->user)
            ->post('admin/provinces', $this->getResource($province, true))
            ->assertSessionDoesntHaveErrors();


        // Leemos el numero de objetos en la base de datos
        $this->assertEquals(1, Province::all()->count());

        // Verificamos que en la base de datos hay un registro con los datos creados
        $this->assertDatabaseHas('provinces', $this->getResource($province));
    }

    /** @test */
    public function usuario_no_puede_crear_un_province_datos_incompletos()
    {
        // Creamos un objeto con datos incompletos
        $province = new Province();

        //  El usuario crea el objeto pero le dice que faltan los required
        $this->actingAs($this->user)->post('admin/provinces', $this->getResource($province, true))
            ->assertSessionHasErrors($this->requiredFields);
    }

    /** @test */
    public function usuario_puede_editar_un_province()
    {
        // Tenemos al menos un objeto en la base de datos
        $province = $this->createObject();

        // El usuario edita el objeto
        $response = $this->actingAs($this->user)->get('admin/provinces/'.$province->id."/edit");

        // Vemos que se ven los datos
        $response->assertSee($province->active);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_editar_un_province()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $province = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $response = $this->put('/admin/provinces/'.$province->id, $this->getResource($province, true))
            ->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_modificar_un_province()
    {
        // Creamos un objeto
        $province = $this->createObject();

        // Modificamos algunos datos
        $province = $this->modifyData($province);

        //  El usuario crea el objeto
        $this->actingAs($this->user)->put('/admin/provinces/'.$province->id, $this->getResource($province, true));

        // Verificamos que en la base de datos hay un registro con los datos modificados
        $this->assertDatabaseHas(
            'provinces',
            $this->getResource($province)
        );
    }

    /** @test */
    public function usuario_no_puede_modificar_un_province_datos_incompletos()
    {
        // Creamos un objeto
        $province = $this->createObject();

        // Modificamos algunos datos
        $province = $this->cleanData($province);

        //  El usuario crea el objeto pero le dice que faltan los required
        $response = $this->actingAs($this->user)->put('/admin/provinces/'.$province->id, $this->getResource($province, true));

        $response->assertSessionHasErrors($this->requiredFields);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_borrar_un_province()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $province = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $response =  $this->delete('/admin/provinces/'.$province->id)
            ->assertRedirect('/admin/login');

        // Verificamos que en la base de datos sigue estando el registro
        if (in_array(SoftDeletes::class, class_uses(get_class($province)))) {
            $this->assertDatabaseHas('provinces', [
                'id' => $province->id,
                'deleted_at' => null,
            ]);
        } else {
            $this->assertDatabaseHas('provinces', ['id'=> $province->id]);
        }
    }

    /** @test */
    public function usuario_puede_borrar_un_province()
    {
        // Creamos un objeto
        $province = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $this->actingAs($this->user)->delete('/admin/provinces/'.$province->id) ;

        // Verificamos que en la base de datos no esta el registro
        if (in_array(SoftDeletes::class, class_uses(get_class($province)))) {
            $this->assertDatabaseHas('provinces', [
                'id' => $province->id,
                'deleted_at' => now(),
            ]);
        } else {
            $this->assertDatabaseMissing('provinces', ['id'=> $province->id]);
        }
    }
}
