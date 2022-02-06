<?php

namespace App\Helpers\Clavel;

use App\Modules\Settings\Models\Setting;

class SettingsHelper
{
    public function get($name)
    {
        $setting = Setting::where('key', $name)->first();
        return $setting->value ?? '';
    }
}
