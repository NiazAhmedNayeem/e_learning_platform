<?php

namespace App\Http\Controllers\backend\course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request){
        $search = $request->input('search');
        $courses = Course::where('status', 1)->get();
        return view('backend.course.index', compact('courses', 'search'));
    }
}
