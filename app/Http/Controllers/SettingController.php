<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\LogHelper;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        if ($request->hasFile('school_logo')) {
            $oldLogo = Setting::get('school_logo');
            if ($oldLogo && $oldLogo != 'default_logo.png') {
                Storage::disk('public')->delete('settings/' . $oldLogo);
            }
            $logoName = 'logo_' . time() . '.' . $request->school_logo->extension();
            $request->school_logo->storeAs('settings', $logoName, 'public');
            $data['school_logo'] = $logoName;
        }

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        LogHelper::log('Memperbarui pengaturan sistem/identitas sekolah');

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
