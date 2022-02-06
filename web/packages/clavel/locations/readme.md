# Clavel CMS Package Paises/Provicias/CCAA/Poblaciones
Paquete de gestion de localizaciones

# Depende del paquete

## Instalación
# Instalacion directa con script
```
COMPOSER_MEMORY_LIMIT=-1 composer update
php artisan migrate:fresh --seed
./packages/clavel/locations/tools/install.sh
```

# Añadir en el composer.json
```
"require": {
    "php": ">=7.1.0",   
    "laravel/framework": "5.7.*",
    ...
    "clavel/locations": "@dev"
  },
```

y

```
,
  "repositories": [
    {
      "type": "path",
      "url": "./packages/clavel/locations/"

    }
  ],
```

si no esta ponemos

```
"minimum-stability": "dev",
```

Llamamos a composer para que reconozca el paquete

```
composer update
```

## Publicación de los contenidos

```
$ php artisan vendor:publish --provider="Clavel\Locations\LocationsServiceProvider"
```

Creamos las tablas de la base de datos 
```
php artisan migrate
```

y añadimos datos

para ello primero lanzamos un 
```
composer dumpauto
```
para que encuentre las clases de seed añadidas. Y luego ejecutamos.

ATENCION: Revisar si queremos las poblaciones ya que son muchas y afectan a las pruebas

Obligatorios
```
php artisan db:seed --class=CountriesPermissionSeeder
php artisan db:seed --class=CcaasPermissionSeeder
php artisan db:seed --class=ProvincesPermissionSeeder
php artisan db:seed --class=CitiesPermissionSeeder
php artisan db:seed --class=LocationsSeeder
```

o bien si no hemos subido la base de datos a producción podriamos añadirlo a /seeds/DatabaseSeeder.php
```
$this->call(CountriesPermissionSeeder::class);
$this->call(CcaasPermissionSeeder::class);
$this->call(ProvincesPermissionSeeder::class);
$this->call(CitiesPermissionSeeder::class);
$this->call(LocationsSeeder::class);
```

   
## API
Añadir en config/l5-swagger.php los paquetes
```
/*
|--------------------------------------------------------------------------
| Absolute path to directory containing the swagger annotations are stored.
|--------------------------------------------------------------------------
*/

'annotations' => [
    base_path('app'),
    base_path('packages/clavel/locations'),

],
```

## Pruebas
Añadir en phpunit.xml
```
<testsuite name="Feature">
    ...
    <directory suffix="Test.php">./packages/clavel/locations/tests/Feature</directory>
</testsuite>
```

Invocar con:

```
php artisan test --filter CountriesTest
```
