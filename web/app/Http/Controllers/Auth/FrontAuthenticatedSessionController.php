<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\UserTwoFactor;
use App\Http\Controllers\Controller;
use App\Notifications\TwoFactorCode;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\FrontLoginRequest;

class FrontAuthenticatedSessionController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('auth/front_lang.bienvenida');

        return view('modules.auth.front_login', compact("page_title"));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\FrontLoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FrontLoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();

        // Verificamos si el usuario tiene activado 2FA y en caso de estar activado generamos token y notificamos
        if ($user->hasTwoFactor()) {
            $two_factor = UserTwoFactor::firstOrNew(array('user_id' => $user->id));

            $two_factor->generateTwoFactorCode();
            $two_factor->save();

            $user->notify(new TwoFactorCode($two_factor));
            return redirect()->route('2fa.verify.index');
        }

        return redirect()->intended($this->redirectTo);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect($this->redirectTo);
    }
}
