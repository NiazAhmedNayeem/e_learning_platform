<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('backend.dashboard.index');
    }

    public function inactive(){
        return view('backend.dashboard.inactive');
    }

    
    
}
