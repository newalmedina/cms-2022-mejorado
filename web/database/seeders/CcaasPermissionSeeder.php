<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Support\Str;

class CcaasPermissionSeeder extends BaseSeeder
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
        $permExists = Permission::where('name', Str::slug('admin-ccaas'))->first();
        if(!empty($permExists)) {
            return;
        }

        // Módulo de ccaas
        $permissions = [
            [
                'display_name' => 'Comunidades autónomas',
                'name' => Str::slug('admin-ccaas'),
                'description' => 'Comunidades autónomas - Módulo'
            ],
            [
                'display_name' => 'Comunidades autónomas - listado',
                'name' => Str::slug('admin-ccaas-list'),
                'description' => 'Comunidades autónomas - lista'
            ],
            [
                'display_name' => 'Comunidades autónomas - crear',
                'name' => Str::slug('admin-ccaas-create'),
                'description' => 'Comunidades autónomas - crear'
            ],
            [
                'display_name' => 'Comunidades autónomas - actualizar',
                'name' => Str::slug('admin-ccaas-update'),
                'description' => 'Comunidades autónomas - actualizar'
            ],
            [
                'display_name' => 'Comunidades autónomas - borrar',
                'name' => Str::slug('admin-ccaas-delete'),
                'description' => 'Comunidades autónomas - borrar'
            ],
            [
                'display_name' => 'Comunidades autónomas - ver',
                'name' => Str::slug('admin-ccaas-read'),
                'description' => 'Comunidades autónomas - ver'
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
