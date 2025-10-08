<?php

namespace App\Http\Controllers\siteSetting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index(){
        return view('site_setting.index');
    }
}
