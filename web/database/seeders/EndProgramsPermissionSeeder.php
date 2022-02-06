<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Support\Str;

class EndprogramsPermissionSeeder extends BaseSeeder
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
        $permExists = Permission::where('name', Str::slug('front-endprograms'))->first();
        if(!empty($permExists)) {
            return;
        }

        // Módulo de endprograms
        $permissions = [
            [
                'display_name' => 'EndPrograms',
                'name' => Str::slug('front-endprograms'),
                'description' => 'EndPrograms - Módulo'
            ],
            [
                'display_name' => 'EndPrograms - listado',
                'name' => Str::slug('front-endprograms-list'),
                'description' => 'EndPrograms - lista'
            ],
            [
                'display_name' => 'EndPrograms - crear',
                'name' => Str::slug('front-endprograms-create'),
                'description' => 'EndPrograms - crear'
            ],
            [
                'display_name' => 'EndPrograms - actualizar',
                'name' => Str::slug('front-endprograms-update'),
                'description' => 'EndPrograms - actualizar'
            ],
            [
                'display_name' => 'EndPrograms - borrar',
                'name' => Str::slug('front-endprograms-delete'),
                'description' => 'EndPrograms - borrar'
            ],
            [
                'display_name' => 'EndPrograms - ver',
                'name' => Str::slug('front-endprograms-read'),
                'description' => 'EndPrograms - ver'
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
