<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Support\Str;

class AlarmsPermissionSeeder extends BaseSeeder
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
        $permExists = Permission::where('name', Str::slug('front-alarms'))->first();
        if (!empty($permExists)) {
            return;
        }

        // Módulo de alarms
        $permissions = [
            [
                'display_name' => 'Alarms',
                'name' => Str::slug('front-alarms'),
                'description' => 'Alarms - Módulo'
            ],
            [
                'display_name' => 'Alarms - listado',
                'name' => Str::slug('front-alarms-list'),
                'description' => 'Alarms - lista'
            ],
            [
                'display_name' => 'Alarms - crear',
                'name' => Str::slug('front-alarms-create'),
                'description' => 'Alarms - crear'
            ],
            [
                'display_name' => 'Alarms - actualizar',
                'name' => Str::slug('front-alarms-update'),
                'description' => 'Alarms - actualizar'
            ],
            [
                'display_name' => 'Alarms - borrar',
                'name' => Str::slug('front-alarms-delete'),
                'description' => 'Alarms - borrar'
            ],
            [
                'display_name' => 'Alarms - ver',
                'name' => Str::slug('front-alarms-read'),
                'description' => 'Alarms - ver'
            ]
        ];

        $MenuChild = $this->insertPermissions($permissions, $this->childAdmin, $this->a_permission_front);

        // Rol de administrador
        $roleAdmin = Role::where("name", "=", Str::slug('admin'))->first();
        if (!empty($this->a_permission_front)) {
            $roleAdmin->attachPermissions($this->a_permission_front);
        }
        $roleUser = Role::where("name", "=", Str::slug('usuario-front'))->first();
        if (!empty($this->a_permission_front)) {
            $roleUser->attachPermissions($this->a_permission_front);
        }
    }
}
