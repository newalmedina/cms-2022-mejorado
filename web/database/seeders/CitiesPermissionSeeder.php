<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Support\Str;

class CitiesPermissionSeeder extends BaseSeeder
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
        $permExists = Permission::where('name', Str::slug('admin-cities'))->first();
        if(!empty($permExists)) {
            return;
        }

        // Módulo de cities
        $permissions = [
            [
                'display_name' => 'Poblaciones',
                'name' => Str::slug('admin-cities'),
                'description' => 'Poblaciones - Módulo'
            ],
            [
                'display_name' => 'Poblaciones - listado',
                'name' => Str::slug('admin-cities-list'),
                'description' => 'Poblaciones - lista'
            ],
            [
                'display_name' => 'Poblaciones - crear',
                'name' => Str::slug('admin-cities-create'),
                'description' => 'Poblaciones - crear'
            ],
            [
                'display_name' => 'Poblaciones - actualizar',
                'name' => Str::slug('admin-cities-update'),
                'description' => 'Poblaciones - actualizar'
            ],
            [
                'display_name' => 'Poblaciones - borrar',
                'name' => Str::slug('admin-cities-delete'),
                'description' => 'Poblaciones - borrar'
            ],
            [
                'display_name' => 'Poblaciones - ver',
                'name' => Str::slug('admin-cities-read'),
                'description' => 'Poblaciones - ver'
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
