<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Support\Str;

class AsignacionCuidadorsPermissionSeeder extends BaseSeeder
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
        $permExists = Permission::where('name', Str::slug('front-asignacioncuidadors'))->first();
        if(!empty($permExists)) {
            return;
        }

        // Módulo de asignacioncuidadors
        $permissions = [
            [
                'display_name' => 'Asignacion Cuidadors',
                'name' => Str::slug('front-asignacioncuidadors'),
                'description' => 'Asignacion Cuidadors - Módulo'
            ],
            [
                'display_name' => 'Asignacion Cuidadors - listado',
                'name' => Str::slug('front-asignacioncuidadors-list'),
                'description' => 'Asignacion Cuidadors - lista'
            ],
            [
                'display_name' => 'Asignacion Cuidadors - crear',
                'name' => Str::slug('front-asignacioncuidadors-create'),
                'description' => 'Asignacion Cuidadors - crear'
            ],
            [
                'display_name' => 'Asignacion Cuidadors - actualizar',
                'name' => Str::slug('front-asignacioncuidadors-update'),
                'description' => 'Asignacion Cuidadors - actualizar'
            ],
            [
                'display_name' => 'Asignacion Cuidadors - borrar',
                'name' => Str::slug('front-asignacioncuidadors-delete'),
                'description' => 'Asignacion Cuidadors - borrar'
            ],
            [
                'display_name' => 'Asignacion Cuidadors - ver',
                'name' => Str::slug('front-asignacioncuidadors-read'),
                'description' => 'Asignacion Cuidadors - ver'
            ]
        ];

        $MenuChild = $this->insertPermissions($permissions, $this->childAdmin, $this->a_permission_front);

        // Rol de administrador
        $roleAdmin = Role::where("name","=", Str::slug('admin'))->first();
        if(!empty($this->a_permission_front)) {
            $roleAdmin->attachPermissions($this->a_permission_front);
        }
        $roleUser = Role::where("name","=", Str::slug('usuario-front'))->first();
        if(!empty($this->a_permission_front)) {
            $roleUser->attachPermissions($this->a_permission_front);
        }
    }
}
