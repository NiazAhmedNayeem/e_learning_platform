<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(){
        return view('teacher.dashboard.index');
    }

    public function profile(){
        $teacher = auth()->user();
        //dd($teacher);
        return view('teacher.profile.index', compact('teacher'));
    }

    public function edit(){
        $teacher = auth()->user();
        $categories = Category::where('status', 1)->get();
        return view('teacher.profile.edit', compact('teacher', 'categories'));
    }

    public function update(Request $request){

        $teacher = auth()->user();

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$teacher->id,
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->phone = $request->phone;
        $teacher->gender = $request->gender;
        $teacher->expertise_category_id = $request->expertise_category_id;
        $teacher->profession = $request->profession;
        $teacher->address = $request->address;
        $teacher->bio = $request->bio;

        if($request->hasFile('image')){ 
            @unlink(public_path("upload/teacher/".$teacher->image));
            $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
            request()->image->move(public_path('upload/teacher/'),$fileName); 
            $teacher->image = $fileName; 
        }
        //dd($teacher);
        $teacher->save();

        return redirect()->route('teacher.profile')->with('success', 'Your profile is updated.');

    }

    public function assignedCourses(Request $request){
        $search = $request->input('search');
        $auth = auth()->user()->id;
        $assign_courses = Course::where('teacher_id', $auth)->where('status', 1)
                ->where(function($query) use ($search){
                $query->where('title', 'like', "%{$search}%");
                })->paginate(5);
        return view('teacher.course.index', compact('assign_courses', 'search'));
    }

    
}
