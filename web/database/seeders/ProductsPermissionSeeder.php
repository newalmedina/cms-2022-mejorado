<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionsTree;
use App\Models\Role;
use Illuminate\Support\Str;

class ProductsPermissionSeeder extends BaseSeeder
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
        $permExists = Permission::where('name', Str::slug('front-products'))->first();
        if(!empty($permExists)) {
            return;
        }

        // Módulo de products
        $permissions = [
            [
                'display_name' => 'Products',
                'name' => Str::slug('front-products'),
                'description' => 'Products - Módulo'
            ],
            [
                'display_name' => 'Products - listado',
                'name' => Str::slug('front-products-list'),
                'description' => 'Products - lista'
            ],
            [
                'display_name' => 'Products - crear',
                'name' => Str::slug('front-products-create'),
                'description' => 'Products - crear'
            ],
            [
                'display_name' => 'Products - actualizar',
                'name' => Str::slug('front-products-update'),
                'description' => 'Products - actualizar'
            ],
            [
                'display_name' => 'Products - borrar',
                'name' => Str::slug('front-products-delete'),
                'description' => 'Products - borrar'
            ],
            [
                'display_name' => 'Products - ver',
                'name' => Str::slug('front-products-read'),
                'description' => 'Products - ver'
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
