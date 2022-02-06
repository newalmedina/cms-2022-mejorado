<?php

namespace Clavel\Locations\Tests\Feature;

use Database\Seeders\CcaasPermissionSeeder;
use Tests\AdminTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Clavel\Locations\Models\Ccaa;

// Para llamar directamente a esta prueba
// ./vendor/bin/phpunit app/Modules/Ccaas/tests/Feature/CcaasTest.php
// ./vendor/bin/phpunit --filter CcaasTest
// ./vendor/bin/phpunit --filter usuario_puede_crear_un_caa
// php artisan test --filter \App\Modules\Ccaas\Tests\Feature\CcaasTest
// php artisan test --filter CcaasTest
// php artisan test --filter usuario_puede_crear_un_caa
// php artisan test --filter CcaasTest::usuario_puede_crear_un_caa

// <testsuite name="Feature">
// ...
// <directory suffix="Test.php">./app/Modules/Ccaas/tests/Feature</directory>
// </testsuite>

class CcaasTest extends AdminTestCase
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
            CcaasPermissionSeeder::class,
        ]);

        // Asignamos permisos al usuario autenticado
        // para que pueda ejecutar las acciones.
        $this->user->syncPermissions([
            'admin-ccaas-list',
            'admin-ccaas-create',
            'admin-ccaas-update',
            'admin-ccaas-delete',
            'admin-ccaas-read',
        ]);
    }

    private function createObject()
    {
        return \Clavel\Locations\Models\Ccaa::factory()->create();
    }

    private function makeObject()
    {
        return \Clavel\Locations\Models\Ccaa::factory()->make();
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
        $response = $this->get(route('ccaas.index'));

        // La respuesta debe ser 200 o 302
        // Si solo hay admin hay una redirección por lo que la respuesta es 302
        $this->assertContains($response->getStatusCode(), array(200, 302));

        // Miramos si la vista es la correcta
        $response->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_leer_ccaas()
    {
        // Tenemos al menos un objeto en la base de datos
        $ccaa = $this->createObject();

        // El usuario visita el listado de objetos
        $response = $this->actingAs($this->user)->post('admin/ccaas/list');

        // Veremos en el listado el objeto creado
        $response->assertSee($ccaa->country_id);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_crear_un_ccaa()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto pero no lo insertamos
        $ccaa = $this->makeObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $this->post('admin/ccaas', $this->getResource($ccaa, true))
            ->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_crear_un_ccaa()
    {
        // Creamos un objeto pero no lo insertamos
        $ccaa = $this->makeObject();

        //  El usuario crea el objeto
        $this->actingAs($this->user)
            ->post('admin/ccaas', $this->getResource($ccaa, true))
            ->assertSessionDoesntHaveErrors();


        // Leemos el numero de objetos en la base de datos
        $this->assertEquals(1, Ccaa::all()->count());

        // Verificamos que en la base de datos hay un registro con los datos creados
        $this->assertDatabaseHas('ccaas', $this->getResource($ccaa));
    }

    /** @test */
    public function usuario_no_puede_crear_un_ccaa_datos_incompletos()
    {
        // Creamos un objeto con datos incompletos
        $ccaa = new Ccaa();

        //  El usuario crea el objeto pero le dice que faltan los required
        $this->actingAs($this->user)->post('admin/ccaas', $this->getResource($ccaa, true))
            ->assertSessionHasErrors($this->requiredFields);
    }

    /** @test */
    public function usuario_puede_editar_un_ccaa()
    {
        // Tenemos al menos un objeto en la base de datos
        $ccaa = $this->createObject();

        // El usuario edita el objeto
        $response = $this->actingAs($this->user)->get('admin/ccaas/' . $ccaa->id . "/edit");

        // Vemos que se ven los datos
        $response->assertSee($ccaa->active);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_editar_un_ccaa()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $ccaa = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $response = $this->put('/admin/ccaas/' . $ccaa->id, $this->getResource($ccaa, true))
            ->assertRedirect('/admin/login');
    }

    /** @test */
    public function usuario_puede_modificar_un_ccaa()
    {
        // Creamos un objeto
        $ccaa = $this->createObject();

        // Modificamos algunos datos
        $ccaa = $this->modifyData($ccaa);

        //  El usuario crea el objeto
        $this->actingAs($this->user)->put('/admin/ccaas/' . $ccaa->id, $this->getResource($ccaa, true));

        // Verificamos que en la base de datos hay un registro con los datos modificados
        $this->assertDatabaseHas(
            'ccaas',
            $this->getResource($ccaa)
        );
    }

    /** @test */
    public function usuario_no_puede_modificar_un_ccaa_datos_incompletos()
    {
        // Creamos un objeto
        $ccaa = $this->createObject();

        // Modificamos algunos datos
        $ccaa = $this->cleanData($ccaa);

        //  El usuario crea el objeto pero le dice que faltan los required
        $response = $this->actingAs($this->user)->put('/admin/ccaas/' . $ccaa->id, $this->getResource($ccaa, true));

        $response->assertSessionHasErrors($this->requiredFields);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_borrar_un_ccaa()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $ccaa = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $response =  $this->delete('/admin/ccaas/' . $ccaa->id)
            ->assertRedirect('/admin/login');

        // Verificamos que en la base de datos sigue estando el registro
        if (in_array(SoftDeletes::class, class_uses(get_class($ccaa)))) {
            $this->assertDatabaseHas('ccaas', [
                'id' => $ccaa->id,
                'deleted_at' => null,
            ]);
        } else {
            $this->assertDatabaseHas('ccaas', ['id' => $ccaa->id]);
        }
    }

    /** @test */
    public function usuario_puede_borrar_un_ccaa()
    {
        // Creamos un objeto
        $ccaa = $this->createObject();

        //  El usuario crea el objeto y deberia enviarnos a login
        $this->actingAs($this->user)->delete('/admin/ccaas/' . $ccaa->id);

        // Verificamos que en la base de datos no esta el registro
        if (in_array(SoftDeletes::class, class_uses(get_class($ccaa)))) {
            $this->assertDatabaseHas('ccaas', [
                'id' => $ccaa->id,
                'deleted_at' => now(),
            ]);
        } else {
            $this->assertDatabaseMissing('ccaas', ['id' => $ccaa->id]);
        }
    }
}
