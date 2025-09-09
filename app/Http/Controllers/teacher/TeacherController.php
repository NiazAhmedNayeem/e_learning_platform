<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function index(){
        return view('teacher.dashboard.index');
    }

    public function profile(){
        $teacher = auth()->user();
        $categories = Category::where('status', 1)->get();
        //dd($teacher);
        return view('teacher.profile.index', compact('teacher', 'categories'));
    }

    // public function edit(){
    //     $teacher = auth()->user();
    //     $categories = Category::where('status', 1)->get();
    //     return view('teacher.profile.edit', compact('teacher', 'categories'));
    // }





    public function update(Request $request){

        $teacher = auth()->user();

        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$teacher->id,
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'expertise_category_id' => 'required|exists:categories,id',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // $request->validate([
        //     'name'        => 'required|string|max:255',
        //     'email'       => 'required|email|unique:users,email,'.$teacher->id,
        //     'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
        //     'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        // ]);

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

        $html = view('teacher.profile.profile_view', compact('teacher'))->render();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile update successfully.',
            'html' => $html,
        ]);

        // return redirect()->route('teacher.profile')->with('success', 'Your profile is updated.');

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

    public function assignedCoursesDetails($slug){
        $course = Course::where('slug', $slug)->first();
        //dd($course);
        return view('teacher.course.details', compact('course'));
    }

    public function totalCourseStudent(Request $request)
    {
        $search = $request->input('search');
        $teacher = auth()->user()->id;

        $courses = Course::where('teacher_id', $teacher)
            ->where('status', 1)
            ->where(function($query) use ($search){
                $query->where('title', 'like', "%{$search}%");
            })->paginate(5);

        return view('teacher.student.index', compact('courses', 'search'));
    }


}
