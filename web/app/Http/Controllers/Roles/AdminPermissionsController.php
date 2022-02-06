<?php

namespace App\Http\Controllers\Roles;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\PermissionsTree;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\AdminController;
use App\Models\Permission;

class AdminPermissionsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->access_permission = 'admin-roles';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Si no tiene permisos para modificar o visualizar lo echamos
        if (!auth()->user()->isAbleTo('admin-roles-update') && !auth()->user()->isAbleTo('admin-roles-read')) {
            app()->abort(403);
        }

        $permissionsTree = PermissionsTree::withDepth()->with('permission')->get()->sortBy('_lft');

        $role = Role::find($id);
        $a_arrayPermisos = $role->getArrayPermissions();

        if (is_null($role)) {
            app()->abort(500);
        }


        return view('modules.roles.admin_permissions_form', compact(
            'permissionsTree',
            'id',
            'role',
            'a_arrayPermisos'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Si no tiene permisos para modificar o visualizar lo echamos
        if (!auth()->user()->isAbleTo('admin-roles-update')) {
            app()->abort(403);
        }

        $idpermissions = explode(",", $request->input('results'));
        $idrole = $request->input("id");

        // Compruebo que el rol al que se quieren asignar datos existe
        $role = Role::find($idrole);

        if (is_null($role)) {
            app()->abort(500);
        }

        try {
            DB::beginTransaction();

            // Asigno el array de permisos al rol
            $role->syncPermissions($idpermissions);

            DB::commit();

            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return redirect()->route('roles.edit', array($idrole, '2'))
                ->with('success', trans('roles/lang.okUpdate_permission'))
                ->with('tab', "tab_2");
        } catch (\PDOException $e) {
            DB::rollBack();
            return redirect()->route('roles.edit', array($idrole, '2'))
                ->with('error', trans('roles/lang.error_en_accion'))
                ->with('tab', "tab_2");
        }
    }

    public function destroy(Request $request, $id, $permission_id)
    {

        // Si no tiene permisos para borrar lo echamos
        if (!auth()->user()->isAbleTo('admin-users-delete')) {
            app()->abort(403);
        }

        // Leemos el role sobre el que operamos
        $role = Role::find($id);
        if (is_null($role)) {
            app()->abort(500);
        }

        // Leemos los permisos del role para despues hacer limpieza
        $a_arrayPermisos = $role->getArrayPermissions();

        // Leemos el permiso que queremos borrar del role
        $permission = Permission::find($permission_id);
        if (is_null($permission)) {
            app()->abort(500);
        }

        // Leemos del arbol vinculado al permiso su informacion
        $permission_tree = PermissionsTree::where('permissions_id', $permission->id)->first();
        if (is_null($permission_tree)) {
            app()->abort(500);
        }

        // Leemos todos los descendentes del arbol y los quitamos del array de permisos del role
        foreach ($permission_tree->descendants as $descendant) {
            unset($a_arrayPermisos[$descendant->permissions_id]);
            // DB::delete('delete from permission_role where permission_id=? and role_id=?', [$descendant->permissions_id, $role->id]);
        }
        unset($a_arrayPermisos[$permission->id]);
        // DB::delete('delete from permission_role where permission_id=? and role_id=?', [$permission->id, $role->id]);

        // Borramos todos los permisos del role a partir del seleccionado y todos sus descendentes
        $permission_tree->delete();


        // Actualizamos los permisos del role para que no queden en cache
        DB::beginTransaction();

        // Asigno el array de permisos al rol
        $role->syncPermissions($a_arrayPermisos);

        DB::commit();




        return Response::json(array(
            'success' => true,
            'msg' => 'Permiso eliminado',
            'id' => $role->id,
            'permission_id' => $permission_id,
        ));
    }
}
