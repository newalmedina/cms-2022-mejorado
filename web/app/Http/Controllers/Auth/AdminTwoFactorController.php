<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\UserTwoFactor;
use App\Http\Controllers\Controller;
use App\Notifications\TwoFactorCode;
use Illuminate\Support\Facades\Hash;

class AdminTwoFactorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'twofactor']);
    }

    public function index()
    {
        $page_title = trans('auth/lang.2fa_authentication');

        return view('modules.auth.admin_twoFactor', compact("page_title"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'integer|required',
        ]);

        $user = auth()->user();

        if ($request->input('two_factor_code') == $user->userTwoFactor->two_factor_code) {
            $user->userTwoFactor->resetTwoFactorCode();
            $user->userTwoFactor->save();

            return redirect()->route('admin');
        }

        return redirect()->back()->withErrors(['two_factor_code' => 'The two factor code you have entered does not match']);
    }

    public function resend()
    {
        $user = auth()->user();


        $two_factor = $user->userTwoFactor;
        $two_factor->generateTwoFactorCode();
        $two_factor->save();

        $user->notify(new TwoFactorCode($two_factor));


        return redirect()->back()->withMessage('The two factor code has been sent again');
    }


    public function enable2fa()
    {
        $user = auth()->user();

        $two_factor = UserTwoFactor::firstOrNew(array('user_id' => $user->id));

        $two_factor->two_factor_enable = true;
        $two_factor->save();

        return redirect('admin/profile')
            ->with('success', "2FA Activado correctamente")
            ->with('tab', "tab_2");
    }

    public function disable2fa(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), auth()->user()->password))) {
            // The passwords matches
            return redirect('admin/profile')
                ->with('error', "Su contraseña no coincide con la contraseña de su cuenta. Inténtalo de nuevo.")
                ->with('tab', "tab_2");
        }

        $user = auth()->user();

        $two_factor = UserTwoFactor::firstOrNew(array('user_id' => $user->id));

        $two_factor->two_factor_enable = false;
        $two_factor->save();

        return redirect('admin/profile')
            ->with('success', "2FA ahora está deshabilitada.")
            ->with('tab', "tab_2");
    }
}
