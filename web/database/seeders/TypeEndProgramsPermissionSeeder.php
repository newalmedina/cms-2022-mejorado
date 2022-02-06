<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Support\Str;

class TypeEndProgramsPermissionSeeder extends BaseSeeder
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
        $permExists = Permission::where('name', Str::slug('admin-typeendprograms'))->first();
        if(!empty($permExists)) {
            return;
        }

        // Módulo de typeendprograms
        $permissions = [
            [
                'display_name' => 'Type End Programs',
                'name' => Str::slug('admin-typeendprograms'),
                'description' => 'Type End Programs - Módulo'
            ],
            [
                'display_name' => 'Type End Programs - listado',
                'name' => Str::slug('admin-typeendprograms-list'),
                'description' => 'Type End Programs - lista'
            ],
            [
                'display_name' => 'Type End Programs - crear',
                'name' => Str::slug('admin-typeendprograms-create'),
                'description' => 'Type End Programs - crear'
            ],
            [
                'display_name' => 'Type End Programs - actualizar',
                'name' => Str::slug('admin-typeendprograms-update'),
                'description' => 'Type End Programs - actualizar'
            ],
            [
                'display_name' => 'Type End Programs - borrar',
                'name' => Str::slug('admin-typeendprograms-delete'),
                'description' => 'Type End Programs - borrar'
            ],
            [
                'display_name' => 'Type End Programs - ver',
                'name' => Str::slug('admin-typeendprograms-read'),
                'description' => 'Type End Programs - ver'
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
