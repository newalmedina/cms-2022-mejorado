<?php
namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use App\Models\PermissionsTree;

class SettingsPermissionSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->init();



        // Agrupador de puntos de menu - Estucctura de la web
        $adminStruct = Permission::where('name', Str::slug('admin'))->first();
        $childAdminStruct = PermissionsTree::where('permissions_id', $adminStruct->id)->first();


        // Si los permisos los hemos creados volvemos
        $permExists = Permission::where('name', Str::slug('admin-settings'))->first();
        if(!empty($permExists)) {
            return;
        }

        // Módulo de settings
        $permissions = [
            [
                'display_name' => 'Configuración',
                'name' => Str::slug('admin-settings'),
                'description' => 'Configuración - Módulo'
            ],
            [
                'display_name' => 'Configuración - listado',
                'name' => Str::slug('admin-settings-list'),
                'description' => 'Configuración - lista'
            ],
            [
                'display_name' => 'Configuración - crear',
                'name' => Str::slug('admin-settings-create'),
                'description' => 'Configuración - crear'
            ],
            [
                'display_name' => 'Configuración - actualizar',
                'name' => Str::slug('admin-settings-update'),
                'description' => 'Configuración - actualizar'
            ],
            [
                'display_name' => 'Configuración - borrar',
                'name' => Str::slug('admin-settings-delete'),
                'description' => 'Configuración - borrar'
            ],
            [
                'display_name' => 'Configuración - ver',
                'name' => Str::slug('admin-settings-read'),
                'description' => 'Configuración - ver'
            ]
        ];

        $MenuChild = $this->insertPermissions($permissions, $childAdminStruct, $this->a_permission_admin);

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
