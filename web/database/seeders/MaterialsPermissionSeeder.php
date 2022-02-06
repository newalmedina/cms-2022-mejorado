<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Support\Str;

class MaterialsPermissionSeeder extends BaseSeeder
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
        $permExists = Permission::where('name', Str::slug('admin-materials'))->first();
        if(!empty($permExists)) {
            return;
        }

        // Módulo de materials
        $permissions = [
            [
                'display_name' => 'Materials',
                'name' => Str::slug('admin-materials'),
                'description' => 'Materials - Módulo'
            ],
            [
                'display_name' => 'Materials - listado',
                'name' => Str::slug('admin-materials-list'),
                'description' => 'Materials - lista'
            ],
            [
                'display_name' => 'Materials - crear',
                'name' => Str::slug('admin-materials-create'),
                'description' => 'Materials - crear'
            ],
            [
                'display_name' => 'Materials - actualizar',
                'name' => Str::slug('admin-materials-update'),
                'description' => 'Materials - actualizar'
            ],
            [
                'display_name' => 'Materials - borrar',
                'name' => Str::slug('admin-materials-delete'),
                'description' => 'Materials - borrar'
            ],
            [
                'display_name' => 'Materials - ver',
                'name' => Str::slug('admin-materials-read'),
                'description' => 'Materials - ver'
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
