<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use App\Facades\Settings;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\FrontRegisterUserRequest;
use App\Notifications\UserCreationNotification;

class FrontRegisteredUserController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('auth/front_lang.registrarse_en_la_web');

        return view('modules.auth.front_register', compact("page_title"));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \App\Http\Requests\FrontRegisterUserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(FrontRegisterUserRequest $request)
    {
        $recaptcha = $request->get("g-recaptcha-response", "");

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'secret' => Settings::get('google_recaptcha_site_key'),
                'response' => $recaptcha,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ],
            CURLOPT_RETURNTRANSFER => true
        ]);

        $output = curl_exec($ch);
        curl_close($ch);
        $captcha_success = json_decode($output);
        if (!$captcha_success->success) {
            // Eres un robot
            return redirect()->back()
                ->withInput()
                ->with('error', trans("auth/front_register.no_robot"));
        }


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
            $user->username = $request->input('email');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->confirmed = true;
            $user->active = true;

            // Guardamos el usuario
            $user->push();

            if ($user->id) {
                $userProfile = new UserProfile();

                $userProfile->first_name = $request->input('user_profile.first_name');
                $userProfile->last_name = $request->input('user_profile.last_name');
                $userProfile->gender = 'male'; //$request->input('userProfile.gender');
                $userProfile->user_lang = App::getLocale(); //$request->input('userProfile.user_lang');
                $userProfile->confirmed = $request->input('user_profile.confirmed', 1);
                // $userProfile->nif = $request->input('nif');
                $userProfile->phone = $request->input('phone', '');

                $user->userProfile()->save($userProfile);

                $roles = Role::select('id')->where("name", "usuario-front")->first()->toArray();

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
