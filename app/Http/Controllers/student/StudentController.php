<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(){
        return view('student.dashboard.index');
    }

    public function profile(){
        $student = auth()->user();
        //dd($student);
        return view('student.profile.index', compact('student'));
    }

    public function edit(){
        $student = auth()->user();
        return view('student.profile.edit', compact('student'));
    }

    public function update(Request $request){

        $student = auth()->user();

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$student->id,
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->gender = $request->gender;
        $student->expertise_category_id = $request->expertise_category_id;
        $student->profession = $request->profession;
        $student->address = $request->address;
        $student->bio = $request->bio;

        if($request->hasFile('image')){ 
            @unlink(public_path("upload/students/".$student->image));
            $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
            request()->image->move(public_path('upload/students/'),$fileName); 
            $student->image = $fileName; 
        }
        //dd($student);
        $student->save();

        return redirect()->route('student.profile')->with('success', 'Your profile is updated.');

    }
}
