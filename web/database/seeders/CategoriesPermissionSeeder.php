<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Support\Str;

class CategoriesPermissionSeeder extends BaseSeeder
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
        $permExists = Permission::where('name', Str::slug('front-categories'))->first();
        if(!empty($permExists)) {
            return;
        }

        // Módulo de categories
        $permissions = [
            [
                'display_name' => 'Categories',
                'name' => Str::slug('front-categories'),
                'description' => 'Categories - Módulo'
            ],
            [
                'display_name' => 'Categories - listado',
                'name' => Str::slug('front-categories-list'),
                'description' => 'Categories - lista'
            ],
            [
                'display_name' => 'Categories - crear',
                'name' => Str::slug('front-categories-create'),
                'description' => 'Categories - crear'
            ],
            [
                'display_name' => 'Categories - actualizar',
                'name' => Str::slug('front-categories-update'),
                'description' => 'Categories - actualizar'
            ],
            [
                'display_name' => 'Categories - borrar',
                'name' => Str::slug('front-categories-delete'),
                'description' => 'Categories - borrar'
            ],
            [
                'display_name' => 'Categories - ver',
                'name' => Str::slug('front-categories-read'),
                'description' => 'Categories - ver'
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
