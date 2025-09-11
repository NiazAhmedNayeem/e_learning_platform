<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $students = User::where('role', 'student')->where('status', 1)->count();
        $teachers = User::where('role', 'teacher')->where('status', 1)->count();
        $categories = Category::where('status', 1)->count();
        $admins = User::where('role', 'admin')->where('status', 1)->count();
        $courses = Course::where('status', 1)->count();
        $assigned_courses = Course::whereNotNull('teacher_id')->where('status', 1)->count();
        return view('backend.dashboard.index', compact('students','teachers','admins','courses','assigned_courses','categories'));
    }



    ///ajax test
    public function ajaxTest(){

        // $categories = Category::all();
        $skills = Skill::where('user_id', auth()->user()->id)->get();
        return view('ajax.index', compact('skills'));
    }

    public function store(Request $request){
        //dd($request->all());
        $skills = $request->input('skill');
        
        
        foreach($skills as $s){
            if(!empty($s)){
                $skill = new Skill();
                $skill->user_id = auth()->user()->id;
                $skill->skill = $s;
                //dd($skill);
                $skill->save();
            }
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Skill added successfully.',
        ]);
        
    }

    public function update(Request $request){
        //dd($request->all());
        $skills = $request->input('skill');
        
        Skill::where('user_id', auth()->user()->id)->delete();
        
        foreach($skills as $s){
            if(!empty($s)){
                $skill = new Skill();
                $skill->user_id = auth()->user()->id;
                $skill->skill = $s;
                //dd($skill);
                $skill->save();
            }
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Skill added successfully.',
        ]);
        
    }

}
