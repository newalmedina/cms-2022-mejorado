<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\FrontController;
use App\Http\Requests\Auth\FrontPasswordExpiredRequest;

class FrontExpiredPasswordController extends FrontController
{
    public function expired()
    {
        $page_title = trans("auth/front_lang.password_expired");

        return view('modules.auth.front_passwords_expired', compact("page_title"));
    }

    public function postExpired(FrontPasswordExpiredRequest $request)
    {
        // Checking current password
        if (!Hash::check($request->current_password, $request->user()->password)) {
            return redirect()->back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        $request->user()->update([
            'password' => bcrypt($request->password),
            'password_changed_at' => Carbon::now()->toDateTimeString()
        ]);
        return redirect()->back()->with(['status' => 'Contraseña cambiada correctamente']);
    }

    public function expiredForgot(Request $request)
    {
        // Aqui llegamos estando logados y debemos ir a recordar constraseña como deslogados
        // Hacemos logout
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('password.request');
    }
}
