<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Support\Str;

class CountriesPermissionSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->init();

        // Si los permisos los hemos creados volvemos
        $permExists = Permission::where('name', Str::slug('admin-countries'))->first();
        if(!empty($permExists)) {
            return;
        }

        // Módulo de countries
        $permissions = [
            [
                'display_name' => 'Paises',
                'name' => Str::slug('admin-countries'),
                'description' => 'Paises - Módulo'
            ],
            [
                'display_name' => 'Paises - listado',
                'name' => Str::slug('admin-countries-list'),
                'description' => 'Paises - lista'
            ],
            [
                'display_name' => 'Paises - crear',
                'name' => Str::slug('admin-countries-create'),
                'description' => 'Paises - crear'
            ],
            [
                'display_name' => 'Paises - actualizar',
                'name' => Str::slug('admin-countries-update'),
                'description' => 'Paises - actualizar'
            ],
            [
                'display_name' => 'Paises - borrar',
                'name' => Str::slug('admin-countries-delete'),
                'description' => 'Paises - borrar'
            ],
            [
                'display_name' => 'Paises - ver',
                'name' => Str::slug('admin-countries-read'),
                'description' => 'Paises - ver'
            ]
        ];

        $MenuChild = $this->insertPermissions($permissions, $this->childAdmin, $this->a_permission_admin);

        // Rol de administrador
        $roleAdmin = Role::where("name","=", Str::slug('admin'))->first();
        if(!empty($this->a_permission_admin)) {
            $roleAdmin->attachPermissions($this->a_permission_admin);
        }
        $roleUser = Role::where("name","=", Str::slug('usuario-front'))->first();
        if(!empty($this->a_permission_front)) {
            $roleUser->attachPermissions($this->a_permission_front);
        }
    }
}
