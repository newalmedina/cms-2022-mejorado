<?php

namespace App\Modules\Centers\tests\Feature;

use Tests\AdminTestCase;
use Illuminate\Support\Facades\DB;
use App\Modules\Centers\Models\Center;
use Database\Seeders\CentersPermissionSeeder;
use Illuminate\Database\Eloquent\SoftDeletes;

// Para llamar directamente a esta prueba
// ./vendor/bin/phpunit app/Modules/Centers/tests/Feature/CentersTest.php
// ./vendor/bin/phpunit --filter CentersTest
// ./vendor/bin/phpunit --filter usuario_puede_crear_un_Centers
// php artisan test --filter \App\Modules\Centers\Tests\Feature\CentersTest
// php artisan test --filter CentersTest
// php artisan test --filter usuario_puede_crear_un_Centers
// php artisan test --filter CentersTest::usuario_puede_crear_un_Centers

// <testsuite name="Feature">
// ...
// <directory suffix="Test.php">./app/Modules/Centers/tests/Feature</directory>
// </testsuite>

class CentersTest extends AdminTestCase
{
    private $requiredFields = [
        "active",
        "name",
        "province_id"
    ];

    private $requiredFieldsLang = [];


    public function setUp(): void
    {
        parent::setUp();

        // Ejecutamos los seeders adicionales para
        // este grupo de tests.
        $this->seed([
            CentersPermissionSeeder::class,
        ]);

        // Asignamos permisos al usuario autenticado
        // para que pueda ejecutar las acciones.
        $this->user->syncPermissions([
            'admin-centers-list',
            'admin-centers-create',
            'admin-centers-update',
            'admin-centers-delete',
            'admin-centers-read',
        ]);
    }

    private function createObject()
    {
        return \App\Modules\Centers\Models\Center::factory()->create();
    }

    private function makeObject()
    {
        return \App\Modules\Centers\Models\Center::factory()->make();
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
        $model->name = $this->faker->name;

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
        $response = $this->get(route('centers.index'));

        // La respuesta debe ser 200 o 302
        // Si solo hay admin hay una redirección por lo que la respuesta es 302
        $this->assertContains($response->getStatusCode(), array(200, 302));

        // Miramos si la vista es la correcta
        $response->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_leer_centers()
    {
        // Tenemos al menos un objeto en la base de datos
        $center = $this->createObject();

        // El usuario visita el listado de objetos
        $response = $this->actingAs($this->user)->post('admin/centers/list');

        // Veremos en el listado el objeto creado
        $response->assertSee($center->name);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_crear_un_center()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto pero no lo insertamos
        $center = $this->makeObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $this->post('admin/centers', $this->getResource($center, true))
            ->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_crear_un_center()
    {
        // Creamos un objeto pero no lo insertamos
        $center = $this->makeObject();

        //  El usuario crea el objeto
        $this->actingAs($this->user)
            ->post('admin/centers', $this->getResource($center, true))
            ->assertSessionDoesntHaveErrors();


        // Leemos el numero de objetos en la base de datos
        $this->assertEquals(1, Center::all()->count());

        // Verificamos que en la base de datos hay un registro con los datos creados
        $this->assertDatabaseHas('centers', $this->getResource($center));
    }

    /** @test */
    public function usuario_no_puede_crear_un_center_datos_incompletos()
    {
        // Creamos un objeto con datos incompletos
        $center = new Center();

        //  El usuario crea el objeto pero le dice que faltan los required
        $this->actingAs($this->user)->post('admin/centers', $this->getResource($center, true))
            ->assertSessionHasErrors($this->requiredFields);
    }

    /** @test */
    public function usuario_puede_editar_un_center()
    {
        // Tenemos al menos un objeto en la base de datos
        $center = $this->createObject();

        // El usuario edita el objeto
        $response = $this->actingAs($this->user)->get('admin/centers/' . $center->id . "/edit");

        // Vemos que se ven los datos
        $response->assertSee($center->name);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_editar_un_center()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $center = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $response = $this->put('/admin/centers/' . $center->id, $this->getResource($center, true))
            ->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_modificar_un_center()
    {
        // Creamos un objeto
        $center = $this->createObject();

        // Modificamos algunos datos
        $center = $this->modifyData($center);

        //  El usuario crea el objeto
        $this->actingAs($this->user)->put('/admin/centers/' . $center->id, $this->getResource($center, true));

        // Verificamos que en la base de datos hay un registro con los datos modificados
        $this->assertDatabaseHas(
            'centers',
            $this->getResource($center)
        );
    }

    /** @test */
    public function usuario_no_puede_modificar_un_center_datos_incompletos()
    {
        // Creamos un objeto
        $center = $this->createObject();

        // Modificamos algunos datos
        $center = $this->cleanData($center);


        //  El usuario crea el objeto pero le dice que faltan los required
        $response = $this->actingAs($this->user)->put('/admin/centers/' . $center->id, $this->getResource($center, true));

        $response->assertSessionHasErrors($this->requiredFields);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_borrar_un_center()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $center = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $response =  $this->delete('/admin/centers/' . $center->id)
            ->assertRedirect('/admin/login');

        // Verificamos que en la base de datos sigue estando el registro
        if (in_array(SoftDeletes::class, class_uses(get_class($center)))) {
            $this->assertDatabaseHas('centers', [
                'id' => $center->id,
                'deleted_at' => null,
            ]);
        } else {
            $this->assertDatabaseHas('centers', ['id' => $center->id]);
        }
    }

    /** @test */
    public function usuario_puede_borrar_un_center()
    {
        // Creamos un objeto
        $center = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $this->actingAs($this->user)->delete('/admin/centers/' . $center->id);

        // Verificamos que en la base de datos no esta el registro
        if (in_array(SoftDeletes::class, class_uses(get_class($center)))) {
            $this->assertDatabaseHas('centers', [
                'id' => $center->id,
                'deleted_at' => now(),
            ]);
        } else {
            $this->assertDatabaseMissing('centers', ['id' => $center->id]);
        }
    }
}
