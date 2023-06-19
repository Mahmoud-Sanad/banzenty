<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Settings;
use Illuminate\Http\UploadedFile;

class SettingsController extends Controller
{
    public function edit()
    {
        $data = Settings::get()->pluck('value', 'name')->toArray();

        return view('admin.settings.general', $data);
    }

    public function update(Request $request)
    {
        $settings = $request->except('_token');

        foreach ($settings as $key => $value) {
            $type = ($value instanceof UploadedFile)
                ? Settings::TYPE_FILE
                : (is_array($value) ? Settings::TYPE_JSON : 1);

            Settings::setValue($key, $value, $type);
        }

        return redirect()->back();
    }

    public function editTermsAndConditions()
    {
        $data['terms_and_conditions'] = Settings::getValue('terms-and-conditions');

        return view('admin.settings.terms-and-conditions', $data);
    }

    public function updateTermsAndConditions(Request $request)
    {
        Settings::setValue('terms-and-conditions', $request->input('text'));

        return redirect()->back();
    }

    public function editPrivacyPolicy()
    {
        $data['privacy_policy'] = Settings::getValue('privacy-policy');

        return view('admin.settings.privacy-policy', $data);
    }

    public function updatePrivacyPolicy(Request $request)
    {
        Settings::setValue('privacy-policy', $request->input('text'));

        return redirect()->back();
    }
}
