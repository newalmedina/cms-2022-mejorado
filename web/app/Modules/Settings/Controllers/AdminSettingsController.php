<?php

namespace App\Modules\Settings\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Modules\Settings\Models\Setting;
use App\Http\Controllers\AdminController;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\Settings\Requests\AdminSettingsRequests;

class AdminSettingsController extends AdminController
{
    protected $page_title_icon = '<i class="fa fa-cog"></i>';


    public function __construct()
    {
        parent::__construct();

        $this->access_permission = 'admin-settings';
    }

    /**
     *  Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-settings-update')) {
            app()->abort(403);
        }

        $page_title = trans("Settings::admin_lang.title");

        $tab_title = trans("Settings::admin_lang.info_menu");

        $settings = Setting::orderBy('order', 'ASC')->get();

        $tab = 'tab_1';

        return view("Settings::admin_edit", compact('settings', 'page_title', 'tab_title', 'tab'))
            ->with('page_title_icon', $this->page_title_icon);
    }

    public function update(AdminSettingsRequests $request)
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-settings-update')) {
            app()->abort(403);
        }

        $settings = Setting::all();

        foreach ($settings as $setting) {
            $setting->value = $request->input($setting->key, "");
            $setting->save();
        }


        return redirect()->to('admin/settings')
            ->with('success', trans('Settings::admin_lang.save_ok'))
            ->withInput();
    }

    /**
     * Display info de envio de email.
     *
     * @return \Illuminate\Http\Response
     */
    public function editMail()
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-settings-update')) {
            app()->abort(403);
        }

        $page_title = trans("Settings::admin_lang.title");

        $tab_title = trans("Settings::admin_lang.test_email");

        $tab = 'tab_2';

        return view("Settings::admin_edit_mail", compact('page_title', 'tab_title', 'tab'))
            ->with('page_title_icon', $this->page_title_icon);
    }

    public function sendMail(Request $request)
    {
        // Si no tiene permisos para ver el listado lo echa.
        if (!auth()->user()->isAbleTo('admin-settings-update')) {
            app()->abort(403);
        }

        try {
            // Enviar mail
            Mail::raw(
                "Esto es un email de prueba.",
                function ($message) use ($request) {
                    $message
                        ->to($request->get('receiver_email', ''))
                        ->subject('Prueba de recepción de email de ' . env("APP_NAME"));
                }
            );
        } catch (Exception $e) {
            return redirect()->back()
            ->with('error', $e->getMessage())
            ->withInput();
        }

        return redirect()->back()
            ->with('success', trans('Settings::admin_lang.send_ok'))
            ->withInput();
    }
}
