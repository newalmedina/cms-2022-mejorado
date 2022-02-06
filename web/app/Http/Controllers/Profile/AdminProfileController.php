<?php

namespace App\Http\Controllers\Profile;

use App\Models\User;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Services\StoragePathWork;
use Illuminate\Support\Facades\DB;
use App\Services\UserCheckLoginPass;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\AdminController;
use App\Http\Requests\AdminProfileRequest;

class AdminProfileController extends AdminController
{
    protected $page_title_icon = '<i class="fa fa-id-card" aria-hidden="true"></i>';



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //Obtengo la información del usuario para pasarsela al formulario
        $user = User::with('userProfile')->find(auth()->user()->getAuthIdentifier());

        // Obtenemos los datos de su ultimo acceso valido y los datos de su navegador
        $datos_acceso = optional($user->authentications()->whereLoginSuccessful(true)->first());
        $agent = new Agent();
        $agent->setUserAgent($datos_acceso->user_agent);

        $page_title = trans('profile/admin_lang.mi_perfil');

        return view('modules.profile.admin_index', compact('page_title', 'user', 'datos_acceso', 'agent'))
            ->with('page_title_icon', $this->page_title_icon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminProfileRequest $request)
    {
        // Id actual
        $idprofile = auth()->user()->getAuthIdentifier();

        // Creamos un nuevo objeto para nuestro nuevo usuario
        $user = User::with('userProfile')->find($idprofile);

        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null($user)) {
            app()->abort(500);
        }

        // Si la data es valida se la asignamos al usuario
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        if (!empty($request->input('password'))) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->userProfile->phone = $request->input('userProfile.phone');
        $user->userProfile->first_name = $request->input('userProfile.first_name');
        $user->userProfile->last_name = $request->input('userProfile.last_name');
        $user->userProfile->gender = $request->input('userProfile.gender');
        $user->userProfile->user_lang = $request->input("userProfile.user_lang");

        try {
            DB::beginTransaction();

            // Guardamos el usuario
            if (!$user->push()) {
                // En caso de error regresa a la acción create con los datos y los errores encontrados
                return redirect('admin/profile')
                    ->withInput($request->except('password'))
                    ->withErrors($user->errors);
            }

            // Redirect to the new user page
            DB::commit();

            Session::put('lang', $user->userProfile->user_lang);

            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return redirect('admin/profile')
                ->with('success', trans('profile/admin_lang.okGuardado'))
                ->with('tab', "tab_1");
        } catch (\PDOException $e) {
            // Woopsy
            DB::rollBack();
            return redirect('admin/profile')
                ->with('error', trans('profile/admin_lang.error_en_accion'))
                ->with('tab', "tab_1");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSocial(Request $request)
    {
        // Id actual
        $idprofile = auth()->user()->getAuthIdentifier();

        // Creamos un nuevo objeto para nuestro nuevo usuario
        $user = User::with('userProfile')->find($idprofile);

        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null($user)) {
            app()->abort(500);
        }

        try {
            // Si la data es valida se la asignamos al usuario
            $user->userProfile->facebook = $request->input('userProfile.facebook', '');
            $user->userProfile->twitter = $request->input('userProfile.twitter', '');
            $user->userProfile->linkedin = $request->input('userProfile.linkedin', '');
            $user->userProfile->youtube = $request->input('userProfile.youtube', '');
            $user->userProfile->bio = $request->input('userProfile.bio', '');

            DB::beginTransaction();

            // Guardamos el usuario
            if (!$user->push()) {
                // En caso de error regresa a la acción create con los datos y los errores encontrados
                return redirect('admin/profile')
                    ->withInput($request->except('password'))
                    ->withErrors($user->errors)
                    ->with('tab', "tab_4");
            }

            // Redirect to the new user page
            DB::commit();

            Session::put('lang', $user->userProfile->user_lang);

            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return redirect('admin/profile')
                ->with('success', trans('profile/admin_lang.okGuardado'))
                ->with('tab', "tab_4");
        } catch (\PDOException $e) {
            // Woopsy
            DB::rollBack();
            return redirect('admin/profile')
                ->with('error', trans('profile/admin_lang.error_en_accion'))
                ->with('tab', "tab_4");
        }
    }

    /**
     * Upload Avatar resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        // Id actual
        $idprofile = auth()->user()->getAuthIdentifier();

        // Creamos un nuevo objeto para nuestro nuevo usuario
        $user = User::with('userProfile')->find($idprofile);

        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null($user)) {
            app()->abort(500);
        }

        $myServiceSPW = new StoragePathWork("users");

        // Si la data es valida se la asignamos al usuario
        if ($request->input("delete_photo")=='1') {
            $myServiceSPW->deleteFile($user->userProfile->photo, '');
            $user->userProfile->photo="";
        }

        try {
            DB::beginTransaction();

            $files = $request->file('profile_image', []);

            foreach ($files as $file) {
                if (!is_null($file)) {
                    $filename = $myServiceSPW->saveFile($file, '');

                    $user->userProfile->photo = $filename;
                }
            }

            // Guardamos el usuario
            if (!$user->push()) {
                // En caso de error regresa a la acción create con los datos y los errores encontrados
                return redirect('admin/profile')
                    ->withErrors($user->errors)
                    ->with('tab', "tab_3");
            }

            // Redirect to the new user page
            DB::commit();

            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return redirect('admin/profile')
                ->with('success', trans('profile/admin_lang.okGuardado'))
                ->with('tab', "tab_3");
        } catch (\PDOException $e) {
            // Woopsy
            DB::rollBack();
            return redirect('admin/profile')
                ->with('error', trans('profile/admin_lang.error_en_accion'))
                ->with('tab', "tab_3");
        }
    }



    public function checkLoginExists(Request $request)
    {
        $check = new UserCheckLoginPass($request->get('user_id'), $request->get('login'));
        return $check->existUserLoginService();
    }

    public function getPhoto($photo)
    {
        $myServiceSPW = new StoragePathWork("users");
        return $myServiceSPW->showFile($photo, '/users');
    }
}
