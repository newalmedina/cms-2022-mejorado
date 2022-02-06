<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Auth\AdminPasswordExpiredRequest;

class AdminExpiredPasswordController extends AdminController
{
    public function expired()
    {
        $page_title = trans('auth/lang.password_expired');

        return view('modules.auth.admin_passwords_expired', compact("page_title"));
    }

    public function postExpired(AdminPasswordExpiredRequest $request)
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

        return redirect()->route('admin.password.request');
    }
}
