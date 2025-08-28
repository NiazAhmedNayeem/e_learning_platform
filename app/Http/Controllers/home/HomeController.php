<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function courses(){
        $courses = Course::where('status', 1)->paginate(30);
        return view('frontend.courses.index', compact('courses'));
    }
    public function checkout($slug){
        $course = Course::where('slug', $slug)->first();
        return view('frontend.courses.checkout', compact('course'));
    }
}
