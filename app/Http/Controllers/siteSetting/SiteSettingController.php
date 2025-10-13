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

        /// -------------------- FAVICON --------------------
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
        
        /// -------------------- SITE LOGO --------------------
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

        /// -------------------- PAYMENT METHODS --------------------
        $paymentMethods = [];

        $names = $request->payment_method_name ?? [];
        $newIcons = $request->file('payment_method_icon') ?? [];
        $oldIcons = $request->old_payment_icon ?? [];

        // old data for unlink
        $existing = SiteSetting::where('key', 'payment_methods')->value('value');
        $existing = $existing ? json_decode($existing, true) : [];

        if (count($names) > 0) {
            foreach ($names as $index => $name) {
                if (!$name) continue;

                $iconName = $oldIcons[$index] ?? null;

                // if give new icon 
                if (isset($newIcons[$index])) {
                    // old icon unlink
                    if (!empty($iconName) && file_exists(public_path('upload/site/payment/' . $iconName))) {
                        @unlink(public_path('upload/site/payment/' . $iconName));
                    }

                    $file = $newIcons[$index];
                    $fileName = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('upload/site/payment/'), $fileName);
                    $iconName = $fileName;
                }

                // new data push
                $paymentMethods[] = [
                    'name' => $name,
                    'icon' => $iconName,
                ];
            }

            // remove icon when remove button click
            if (!empty($existing)) {
                foreach ($existing as $old) {
                    $stillExists = collect($paymentMethods)->contains('icon', $old['icon']);
                    if (!$stillExists && !empty($old['icon']) && file_exists(public_path('upload/site/payment/' . $old['icon']))) {
                        @unlink(public_path('upload/site/payment/' . $old['icon']));
                    }
                }
            }

            $data['payment_methods'] = json_encode($paymentMethods);
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
            if ($set->key === 'payment_methods') {
                $arr[$set->key] = json_decode($set->value, true);
            } else {
                $arr[$set->key] = $set->value;
            }
        }
        return $arr;
    }

}
