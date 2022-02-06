<?php

namespace App\Modules\Products\Tests\Feature;

use Tests\FrontTestCase;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Modules\Products\Models\Product;
use Laravel\Passport\ClientRepository;


// Para llamar directamente a esta prueba
// ./vendor/bin/phpunit app/Modules/Products/tests/Feature/ProductsApiTest.php
//  ./vendor/bin/phpunit --filter ProductsApiTest

class ProductsApiTest extends FrontTestCase {

    protected $client, $token, $headers;

    protected function setUp(): void {

        parent::setUp();

        // Creamos el OAuth Client
        $clientRepository = new ClientRepository();
        $this->client = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', '/'
        );

        // Creamos el Personal Access Token
        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $this->client->id,
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        // El usuario ya esta creado en la base de FrontTestCase
        // Creamos el token temporal para el usuario
        $this->token = $this->user->createToken('TestToken', [])->accessToken;
        $this->headers['Accept'] = 'application/json';
        $this->headers['Authorization'] = 'Bearer '.$this->token;
    }


    /** @test */
    public function usuario_no_autenticado_no_puede_acceder()
    {
        // Debo ser invitado
        $this->assertGuest();

        // Llamamos a la ruta de listado
        $response = $this->get('/api/v1/products');

        // La respuesta debe ser 401. Unauthorized
        $response->assertStatus(401);
    }

    /** @test */
    public function usuario_puede_leer_products()
    {

        // Tenemos al menos un objeto en la base de datos
        $product = \App\Modules\Products\Models\Product::factory()->create();

        // Se probaran 3 tipo de acceso que todos son compatibles

        // 1.- Mediante el driver nativo de Laravel
        // El usuario visita el listado de objetos
        $response = $this->actingAs($this->user, 'api')->get('/api/v1/products');

        // Respuesta 200
        $response->assertStatus(200);

        // Veremos en el listado el objeto creado
        $response->assertSee($product->name);

        // 2.- Mediante el driver nativo y llamada a json
        // El usuario visita el listado de objetos en modo json
        $response = $this->actingAs($this->user, 'api')->json('get', '/api/v1/products');

        // Respuesta 200
        $response->assertStatus(200);

        // Veremos en el listado el objeto creado
        $response->assertSee($product->name);

        // 3.- Mediante una cabecera de Bearer
        // El usuario visita el listado de objetos en modo json y ademas el usuario es enviado como token bearer
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('get', '/api/v1/products');

        // Respuesta 200
        $response->assertStatus(200);

        // Veremos en el listado el objeto creado
        $response->assertSee($product->name);

        $response->assertJsonStructure([
            'data' => [
                [
                    "description",
"price",
"active",
"has_taxes",
"name"
                ]
            ]
        ]);

        // Verificamos que el dato es el mismo que hemos grabado
        $response->assertJsonFragment([
            'name' => $product->name
        ]);

    }

    /** @test */
    public function usuario_no_autenticado_no_puede_crear_un_product()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto pero no lo insertamos
        $product = \App\Modules\Products\Models\Product::factory()->make();

        //  El usuario crea el objeto y deberia darnos error 401
       $this->post('/api/v1/products',$product->toArray())
            ->assertStatus(401);

    }

    /** @test */
    public function usuario_puede_crear_un_product()
    {
        // Creamos un objeto pero no lo insertamos
        $product = \App\Modules\Products\Models\Product::factory()->make();

        //  El usuario crea el objeto
        $response = $this->actingAs($this->user, 'api')
            ->post('/api/v1/products',$product->toArray())
            ->assertStatus(201);

        // Leemos el numero de objetos en la base de datos
        $this->assertEquals(1, Product::all()->count());

        // Verificamos que en la base de datos hay un registro con los datos creados
        $this->assertDatabaseHas('products', $product->toArray());

        // Verificamos que el name es el mismo que hemos grabado
        $response->assertJsonFragment([
            'name' => $product->name
        ]);

    }

    /** @test */
    public function usuario_no_puede_crear_un_product_datos_incompletos()
    {
        // Creamos un objeto con datos incompletos
        $product = new Product();

        //  El usuario crea el objeto pero le dice que faltan los required
        $response = $this->actingAs($this->user, 'api')
            ->post('/api/v1/products',$product->toArray())
            ->assertStatus(422);

    }

    /** @test */
    public function usuario_puede_editar_un_product()
    {
        // Tenemos al menos un objeto en la base de datos
        $product = \App\Modules\Products\Models\Product::factory()->create();

        // El usuario edita el objeto
        $response = $this->actingAs($this->user, 'api')
            ->get('/api/v1/products/'.$product->id)
            ->assertStatus(200);

        // Verificamos que el name es el mismo que hemos grabado
        $response->assertJsonFragment([
            'name' => $product->name
        ]);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_editar_un_product()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $product = \App\Modules\Products\Models\Product::factory()->create();

        //  El usuario crea el objeto y deberia decirnos que no podemos
        $this->json('PATCH', '/api/v1/products/'.$product->id, $product->toArray())
            ->assertStatus(401);
    }

    /** @test */
    public function usuario_puede_modificar_un_product()
    {
        // Creamos un objeto
        $product = \App\Modules\Products\Models\Product::factory()->create();

        // Modificamos algunos datos
        $product->name = $this->faker->name;

        //  El usuario crea el objeto
        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/v1/products/'.$product->id, $product->toArray())
            ->assertStatus(200);

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

        // Verificamos que el name es el mismo que hemos grabado
        $response->assertJsonFragment([
            'name' => $product->name
        ]);


    }

    /** @test */
    public function usuario_no_puede_modificar_un_product_datos_incompletos()
    {
        // Creamos un objeto
        $product = \App\Modules\Products\Models\Product::factory()->create();

        // Modificamos algunos datos
        $product->name = '';

        //  El usuario crea el objeto
        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/v1/products/'.$product->id, $product->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_borrar_un_product()
    {

        // Debo ser invitado
        $this->assertGuest();

        // Creamos un objeto
        $product = \App\Modules\Products\Models\Product::factory()->create();

        //  El usuario crea el objeto y deberia enviarnos a login
        $response =  $this->json('DELETE', '/api/v1/products/'.$product->id)
            ->assertStatus(401);

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

        //  El usuario crea el objeto y deberia indicarnos que no podemos
        $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/v1/products/'.$product->id)
            ->assertStatus(204);

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
