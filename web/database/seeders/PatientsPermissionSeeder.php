<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Support\Str;

class PatientsPermissionSeeder extends BaseSeeder
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
        $permExists = Permission::where('name', Str::slug('front-patients'))->first();
        if(!empty($permExists)) {
            return;
        }

        // Módulo de patients
        $permissions = [
            [
                'display_name' => 'Patients',
                'name' => Str::slug('front-patients'),
                'description' => 'Patients - Módulo'
            ],
            [
                'display_name' => 'Patients - listado',
                'name' => Str::slug('front-patients-list'),
                'description' => 'Patients - lista'
            ],
            [
                'display_name' => 'Patients - crear',
                'name' => Str::slug('front-patients-create'),
                'description' => 'Patients - crear'
            ],
            [
                'display_name' => 'Patients - actualizar',
                'name' => Str::slug('front-patients-update'),
                'description' => 'Patients - actualizar'
            ],
            [
                'display_name' => 'Patients - borrar',
                'name' => Str::slug('front-patients-delete'),
                'description' => 'Patients - borrar'
            ],
            [
                'display_name' => 'Patients - ver',
                'name' => Str::slug('front-patients-read'),
                'description' => 'Patients - ver'
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
