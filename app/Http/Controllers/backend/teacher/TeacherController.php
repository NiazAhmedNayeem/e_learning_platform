<?php

namespace App\Http\Controllers\backend\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(){
        return view('teacher.dashboard.index');
    }
}
