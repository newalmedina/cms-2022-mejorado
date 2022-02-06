<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Support\Str;

class TypeAlarmsPermissionSeeder extends BaseSeeder
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
        $permExists = Permission::where('name', Str::slug('admin-typealarms'))->first();
        if(!empty($permExists)) {
            return;
        }

        // Módulo de typealarms
        $permissions = [
            [
                'display_name' => 'Type Alarms',
                'name' => Str::slug('admin-typealarms'),
                'description' => 'Type Alarms - Módulo'
            ],
            [
                'display_name' => 'Type Alarms - listado',
                'name' => Str::slug('admin-typealarms-list'),
                'description' => 'Type Alarms - lista'
            ],
            [
                'display_name' => 'Type Alarms - crear',
                'name' => Str::slug('admin-typealarms-create'),
                'description' => 'Type Alarms - crear'
            ],
            [
                'display_name' => 'Type Alarms - actualizar',
                'name' => Str::slug('admin-typealarms-update'),
                'description' => 'Type Alarms - actualizar'
            ],
            [
                'display_name' => 'Type Alarms - borrar',
                'name' => Str::slug('admin-typealarms-delete'),
                'description' => 'Type Alarms - borrar'
            ],
            [
                'display_name' => 'Type Alarms - ver',
                'name' => Str::slug('admin-typealarms-read'),
                'description' => 'Type Alarms - ver'
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
