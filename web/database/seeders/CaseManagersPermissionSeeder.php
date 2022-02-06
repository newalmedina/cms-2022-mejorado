<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Support\Str;

class CaseManagersPermissionSeeder extends BaseSeeder
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
        $permExists = Permission::where('name', Str::slug('front-casemanagers'))->first();
        if(!empty($permExists)) {
            return;
        }

        // Módulo de casemanagers
        $permissions = [
            [
                'display_name' => 'Case Managers',
                'name' => Str::slug('front-casemanagers'),
                'description' => 'Case Managers - Módulo'
            ],
            [
                'display_name' => 'Case Managers - listado',
                'name' => Str::slug('front-casemanagers-list'),
                'description' => 'Case Managers - lista'
            ],
            [
                'display_name' => 'Case Managers - crear',
                'name' => Str::slug('front-casemanagers-create'),
                'description' => 'Case Managers - crear'
            ],
            [
                'display_name' => 'Case Managers - actualizar',
                'name' => Str::slug('front-casemanagers-update'),
                'description' => 'Case Managers - actualizar'
            ],
            [
                'display_name' => 'Case Managers - borrar',
                'name' => Str::slug('front-casemanagers-delete'),
                'description' => 'Case Managers - borrar'
            ],
            [
                'display_name' => 'Case Managers - ver',
                'name' => Str::slug('front-casemanagers-read'),
                'description' => 'Case Managers - ver'
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
