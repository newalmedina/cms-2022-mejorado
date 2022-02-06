# Clavel PSMS CMS 


## A BASE

### Paquete Basic
* Ponemos todos los puntos sobre un solo menu
MenuSeeder.php

* Compilacion con watch
package.json
* Evitar que ponga las imagenes en images/
webpack.mis.front.js
* mix en layouts
* compilacion de sass en thema y default_skin



## Arranque proyecto
Creamos la base de datos y configuramos el .env

```
 ./dev-tools/start.sh
```

```
COMPOSER_MEMORY_LIMIT=-1 composer update
php artisan migrate:fresh --seed
```


## Instalación y puesta en marcha
Seguir los pasos de instalación de Laravel

Permisos

find . -type f -exec chmod 664 {} \;   
find . -type d -exec chmod 775 {} \;

chmod -R ug+rwx storage bootstrap/cache

Instalamos los paquetes de node + herramientas 
```
npm install --no-bin-links
```

Si da el error

sh: 1: cross-env: not found

tenemos que instalar el cross-env
```
npm install --global cross-env
```

Si da error por todo es porque tenemos abierto VS Code y/o SourceTree. Los cerramos, borramos node_modules y volvemos ejectuarse


Si da fallos de que no encuentra directorios cerramos el Visual Studio o PHP Storm y ejecutamos
```
rm -rf node_modules
rm package-lock.json
npm i
```

a partir de aqui podemos compilar los recursos cargados con npm y que estan el el fichero webpack.mix.admin.js con
```
npm run da
```
con
```
npm run daw
```
para 'd'esarrollo 'a'dmin y
para 'd'esarrollo 'a'dmin 'w'atching

si cambiamos la 'a' por 'f' es de front

Atención porque esto deja en la carpeta css y js las versiones de desarrollo y no estan optimizadas. Si queremos la versión producción es
```
npm run pa
```
y para front
```
npm run pf
```

## Administracion + FronEnd
En /config/general tenemos la variable

'only_backoffice' => true,

Si sólo queremos administración lo dejamos a true, en la ruta / 

`
Route::get('/', 'Home\FrontHomeController@index')->name('home');
`

hay una redirección hacia la
ruta de /admin

## Limpieza de paquetes anteriores

```
git checkout .
git clean -df
```


## Verificación del código
Cómo buena practica mantener PSR-2. Para ello utilizar las herramientas que estan en la carpeta **dev-tools**

Para comprobar el estilo de código ejecutar
```
./dev-tools/cs.sh
```
Este fichero se puede editar y añadir o quitar aquellos modulos que se consideren

Para fijar los errores que estan marcados con una x como automatizables utilizar el siguiente comando
```
./dev-tools/cbf.sh
```

Para fijar los estilos 
```
./dev-tools/cf.sh
```

Para detectar vulnerabilidades
```
./dev-tools/phpstan.sh
```

Para detectar copy pastes
```
./dev-tools/cpd.sh
```

Para detectar vulnerabilidades en paquetes. ESTE PAQUETE ESTA DEPRECATED. Se tendria que usar https://github.com/fabpot/local-php-security-checker
```
./dev-tools/security-checker.sh
```


Si sale este error en cualquiera de los dos anteriores

> bash: ./dev-tools/cs.sh: /bin/bash^M: bad interpreter: No such file or directory

Utilizar la siguiente solución
> To fix, open your script with vi or vim and enter in vi command mode (key Esc), then type this:
>   
>   :set fileformat=unix
>   Finally save it
>   
>   :x! or :wq!
o bien entramos en el Visual Studio Code y en la parte inferior derecha que pone CRLF lo presionamos y cambiamos a LF




# Varios
Si sale este error

 Key path "file:///var/www/html/clavel-cms-2019/web/storage/oauth-private.key" does not exist or is not readable
 
Se tienen que instalar las keys de passport
Instalación claves OAuth iniciales de Passport
php artisan passport:install
php artisan passport:client --personal


# php artisan
php artisan route:list --name=<nombre a buscar>


# PHP unit

Debemos crear la base de datos de pruebas
clavel2021-testing

```
./vendor/bin/phpunit
```
ó
```
$ php artisan test
```

./vendor/bin/phpunit --coverage-html coverage/
o llama a
composer test-coverage

Ejecutar una clase 
./vendor/bin/phpunit  --filter AuthenticationTest

$ php artisan test --filter AuthenticationTest

Ejecutar un método
./vendor/bin/phpunit --filter test_login_screen_can_be_rendered

$ php artisan test --filter test_login_screen_can_be_rendered


Crear una prueba
php artisan make:test Api/PostTest --unit

Windows
cd proyectos\www\clavel-cms-2019\web\
D:\software\Sonar\sonar-scanner-4.3.0.2102-windows\bin\sonar-scanner -Dproject.settings=.\sonar-project.properties

Linux (Este tarda la vida)
sonar-scanner -Dproject.settings=./sonar-project.properties

php -dzend_extension=xdebug.so ./vendor/bin/phpunit --configuration phpunit.xml --coverage-clover phpunit.coverage.xml --log-junit phpunit.report.xml

