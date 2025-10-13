<?php

namespace App\Http\Controllers\siteSetting;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteSettingController extends Controller
{
    public function index(){
        $settings = SiteSettingController::getSettings();
        return view('site_setting.index', compact('settings'));
    }

    public function ajaxSave(Request $request)
    {
        $data = $request->except('_token');

        ///fav icon
        $oldIcon = SiteSetting::where('key', 'favicon')->value('value');

        if ($request->hasFile('favicon')) {
            if (!empty($oldIcon) && file_exists(public_path('upload/site/' . $oldIcon))) {
                @unlink(public_path('upload/site/' . $oldIcon));
            }
            $file = $request->file('favicon');
            $fileName = time() . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/site/'), $fileName);
            $data['favicon'] = $fileName;
        }
        
        ///site logo
        $oldLogo = SiteSetting::where('key', 'site_logo')->value('value');

        if ($request->hasFile('site_logo')) {
            if (!empty($oldLogo) && file_exists(public_path('upload/site/' . $oldLogo))) {
                @unlink(public_path('upload/site/' . $oldLogo));
            }
            $file = $request->file('site_logo');
            $fileName = time() . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/site/'), $fileName);
            $data['site_logo'] = $fileName;
        }

        // Save each field into site_settings table
        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value]
            );
        }

        return response()->json(['status'=>'success','message'=>'Settings saved successfully!'], 200);
    }

    // Optional: get all settings for Blade
    public static function getSettings()
    {
        $settings = SiteSetting::all();
        $arr = [];
        foreach($settings as $set){
            $arr[$set->key] = $set->value;
        }
        return $arr;
    }
}
