<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AdminController;
use App\Http\Requests\AdminApiTokenRequest;
use App\Http\Requests\AdminUsersSocialRequest;

class AdminApiTokensController extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->access_permission = 'admin-users';
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
        if (!auth()->user()->isAbleTo('admin-users-update') && !auth()->user()->isAbleTo('admin-users-read')) {
            app()->abort(403);
        }

        $user = User::with('userProfile')->find($id);
        if (is_null($user)) {
            app()->abort(500);
        }

        $form_data = array(
            'route' => array('admin.users.api.update', $user->id), 'method' => 'PATCH',
            'id' => 'formDataApi', 'class' => 'form-horizontal'
        );

        $tokens_list = $user->tokens()->select('id', 'name', 'token')->get();

        return view('modules.users.admin_api_form', compact('id', 'user', 'form_data', 'tokens_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminApiTokenRequest $request, $id)
    {
        // Si no tiene permisos para modificar o visualizar lo echamos
        if (!auth()->user()->isAbleTo('admin-users-update')) {
            app()->abort(403);
        }

        $iduser = $request->input("id");

        // Compruebo que el rol al que se quieren asignar datos existe
        $user = User::find($iduser);

        if (is_null($user)) {
            app()->abort(500);
        }

        try {
            // Si la data es valida se la asignamos al usuario
            $api_token_name = $request->input('api_token');

            $token = $user->createToken($api_token_name)->plainTextToken;

            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return redirect()->route('users.edit', array($iduser, '5'))
                ->with('success', trans('users/lang.okUpdate_api'))
                ->with('tab', "tab_5");
        } catch (\PDOException $e) {
            DB::rollBack();
            return redirect()->route('users.edit', array($iduser, '5'))
                ->with('error', trans('users/lang.errorediciion'))
                ->with('tab', "tab_5");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // Si no tiene permisos para modificar o visualizar lo echamos
        if (!auth()->user()->isAbleTo('admin-users-update')) {
            app()->abort(403);
        }

        $tokenId = $request->input("token_id");

        // Compruebo que el rol al que se quieren asignar datos existe
        $user = User::find($id);

        if (is_null($user)) {
            app()->abort(500);
        }

        try {
            // Revoke a specific token...
            $user->tokens()->where('id', $tokenId)->delete();

            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return redirect()->route('users.edit', array($user->id, '5'))
                ->with('success', trans('users/lang.okUpdate_api'))
                ->with('tab', "tab_5");
        } catch (\PDOException $e) {
            DB::rollBack();
            return redirect()->route('users.edit', array($user->id, '5'))
                ->with('error', trans('users/lang.errorediciion'))
                ->with('tab', "tab_5");
        }
    }
}
