<?php

namespace App\Modules\Products\Tests\Feature;

use Tests\FrontTestCase;
use App\Modules\Products\Models\Product;
use Database\Seeders\ProductsPermissionSeeder;
use Illuminate\Database\Eloquent\SoftDeletes;


// Para llamar directamente a esta prueba
// ./vendor/bin/phpunit app/Modules/Products/tests/Feature/ProductsTest.php
//  ./vendor/bin/phpunit --filter ProductsTest

class ProductsTest extends FrontTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutamos los seeders adicionales para
        // este grupo de tests.
        $this->seed([
            ProductsPermissionSeeder::class,
        ]);

        // Asignamos permisos al usuario autenticado
        // para que pueda ejecutar las acciones.
        $this->user->syncPermissions([
            'front-products-list',
            'front-products-create',
            'front-products-update',
            'front-products-delete',
            'front-products-read',
        ]);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_acceder()
    {
        // Debo ser invitado
        $this->assertGuest();

        // Llamamos a la ruta de listado
        $response = $this->get(route('products.index'));

        // La respuesta debe ser 200 o 302
        // Si solo hay front hay una redirección por lo que la respuesta es 302
        $this->assertContains($response->getStatusCode(), array(200,302));

        // Miramos si la vista es la correcta
        $response->assertRedirect('/front/login');

    }

    /** @test */
    public function usuario_puede_leer_products()
    {
        // Tenemos al menos un objeto en la base de datos
        $product = \App\Modules\Products\Models\Product::factory()->create();

        // El usuario visita el listado de objetos
        $response = $this->actingAs($this->user)->post('front/products/list');

        // Veremos en el listado el objeto creado
        $response->assertSee($product->name);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_crear_un_product()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto pero no lo insertamos
        $product = \App\Modules\Products\Models\Product::factory()->make();

        //  El usuario crea el objeto y deberia enviarnos a login
        $this->post('front/products',$product->toArray())
            ->assertRedirect('/front/login');

    }

    /** @test */
    public function usuario_puede_crear_un_product()
    {
        // Creamos un objeto pero no lo insertamos
        $product = \App\Modules\Products\Models\Product::factory()->make();

        //  El usuario crea el objeto
        $this->actingAs($this->user)
            ->post('front/products',$product->toArray())
            ->assertSessionDoesntHaveErrors();


        // Leemos el numero de objetos en la base de datos
        $this->assertEquals(1, Product::all()->count());

        // Verificamos que en la base de datos hay un registro con los datos creados
        $this->assertDatabaseHas('products', $product->toArray());

    }

    /** @test */
    public function usuario_no_puede_crear_un_product_datos_incompletos()
    {
        // Creamos un objeto con datos incompletos
        $product = new Product();

        //  El usuario crea el objeto pero le dice que faltan los required
        $this->actingAs($this->user)->post('front/products',$product->toArray())
            ->assertSessionHasErrors([
                "description",
"price",
"active",
"has_taxes",
"name"
            ]);

    }

    /** @test */
    public function usuario_puede_editar_un_product()
    {
        // Tenemos al menos un objeto en la base de datos
        $product = \App\Modules\Products\Models\Product::factory()->create();

        // El usuario edita el objeto
        $response = $this->actingAs($this->user)->get('front/products/'.$product->id."/edit");

        // Vemos que se ven los datos
        $response->assertSee($product->name);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_editar_un_product()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $product = \App\Modules\Products\Models\Product::factory()->create();

        //  El usuario crea el objeto y deberia enviarnos a login
        $response = $this->put('/front/products/'.$product->id, $product->toArray())
            ->assertRedirect('/front/login');

    }

    /** @test */
    public function usuario_puede_modificar_un_product()
    {
        // Creamos un objeto
        $product = \App\Modules\Products\Models\Product::factory()->create();

        // Modificamos algunos datos
        $product->name = $this->faker->name;

        //  El usuario crea el objeto
        $this->actingAs($this->user)->put('/front/products/'.$product->id, $product->toArray());

        // Verificamos que en la base de datos hay un registro con los datos modificados
        $this->assertDatabaseHas('products',
            [
                'id' => $product->id,
                "description" => $product->description,
"price" => $product->price,
"active" => $product->active,
"has_taxes" => $product->has_taxes,
"name" => $product->name
            ]
        );

    }

    /** @test */
    public function usuario_no_puede_modificar_un_product_datos_incompletos()
    {
        // Creamos un objeto
        $product = \App\Modules\Products\Models\Product::factory()->create();

        // Modificamos algunos datos
        $product->description = "";
$product->price = "";
$product->active = "";
$product->has_taxes = "";
$product->name = "";


        //  El usuario crea el objeto pero le dice que faltan los required
        $this->actingAs($this->user)->post('front/products',$product->toArray())
            ->assertSessionHasErrors([
                "description",
"price",
"active",
"has_taxes",
"name"
            ]);

    }

    /** @test */
    public function usuario_no_autenticado_no_puede_borrar_un_product()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $product = \App\Modules\Products\Models\Product::factory()->create();

        //  El usuario crea el objeto y deberia enviarnos a login
        $response =  $this->delete('/front/products/'.$product->id)
            ->assertRedirect('/front/login');

        // Verificamos que en la base de datos sigue estando el registro
        if (in_array(SoftDeletes::class, class_uses(get_class($product)))) {
            $this->assertDatabaseHas('products', [
                'id' => $product->id,
                'deleted_at' => null,
            ]);
        }
        else {
            $this->assertDatabaseHas('products', ['id'=> $product->id]);
        }
    }

    /** @test */
    public function usuario_puede_borrar_un_product()
    {
        // Creamos un objeto
        $product = \App\Modules\Products\Models\Product::factory()->create();

        //  El usuario crea el objeto y deberia enviarnos a login
        $this->actingAs($this->user)->delete('/front/products/'.$product->id) ;

        // Verificamos que en la base de datos no esta el registro
        if (in_array(SoftDeletes::class, class_uses(get_class($product)))) {
            $this->assertDatabaseHas('products', [
                'id' => $product->id,
                'deleted_at' => now(),
            ]);
        }
        else {
            $this->assertDatabaseMissing('products', ['id'=> $product->id]);
        }
    }
}
