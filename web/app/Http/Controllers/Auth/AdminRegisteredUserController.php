<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserCreationNotification;

class AdminRegisteredUserController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('auth/admin_lang.registrarse_en_la_web');

        return view('modules.auth.admin_register', compact("page_title"));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);



        // $user = User::create([
        //     'username' => $request->username,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        try {
            DB::beginTransaction();

            // Creamos un nuevo objeto para nuestro nuevo usuario y su relación
            $user = new User();

            // Obtenemos los datos enviados por el usuario
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->confirmed = !empty($request->input('terms', ''));
            $user->active = true;

            // Guardamos el usuario
            $user->push();

            if ($user->id) {
                $userProfile = new UserProfile();

                $userProfile->first_name = $request->input('username'); //$request->input('userProfile.first_name');
                $userProfile->last_name = $request->input('email'); //$request->input('userProfile.last_name');
                $userProfile->gender = 'male'; //$request->input('userProfile.gender');
                $userProfile->user_lang = 'es'; //$request->input('userProfile.user_lang');

                $user->userProfile()->save($userProfile);

                $roles = Role::select('id')->where("name", "admin")->first()->toArray();

                $user->roles()->sync([]);
                $user->roles()->attach($roles);

                $this->notificarAdminitradores($user);

                DB::commit();

            // Y Devolvemos una redirección a la acción show para mostrar el usuario
                // return redirect()->back()
                //     ->with('success', trans('users/lang.okGuardado'));
            } else {
                DB::rollBack();
                // En caso de error regresa a la acción create con los datos y los errores encontrados
                return redirect()->back()
                    ->withInput($request->except('password'))
                    ->withErrors($user->errors);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput($request->except('password'))
                ->with('error', $e->getMessage());
            // ->with('error', trans('users/lang.error_en_accion'));
        }


        event(new Registered($user));

        Auth::login($user);

        return redirect($this->redirectTo);
    }


    protected function notificarAdminitradores(User $user)
    {
        $admins = User::withRole('admin')->get();
        foreach ($admins as $admin) {
            Notification::send($admin, new UserCreationNotification($user));
        }
    }
}
