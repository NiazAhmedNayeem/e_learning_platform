<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request) {
        $search = $request->input('search');

        $students = Student::when($search, function($query, $search){
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
        })->paginate(10);


        return view('backend.student.index', compact('students', 'search'));
    }

    public function create(){
        return view('backend.student.create');
    }

    public function store(Request $request){
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:students,email',
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
            'class'       => 'required|string|max:100',
            'section'     => 'required|string|max:50',
            'gender'      => 'required|in:male,female,other',
            'age'         => 'required|',
            'dob'         => 'required|date|before:today',
            'pre_school'  => 'required|string|max:255',
            'pre_class'   => 'required|string|max:255',
            'pre_section' => 'required|string|max:255',
            'image'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt('123456');
            $user->role = 'student';
            $user->save();

            $student = new Student();
            $student->user_id = $user->id;
            $student->name = $request->name;
            $student->email = $request->email;
            $student->phone = $request->phone;
            $student->class = $request->class;
            $student->section = $request->section;
            $student->gender = $request->gender;
            $student->age = $request->age;
            $student->dob = $request->dob;
            $student->pre_school = $request->pre_school;
            $student->pre_class = $request->pre_class;
            $student->pre_section = $request->pre_section;

            if($request->hasFile('image')){ 
                $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
                request()->image->move(public_path('upload/students/'),$fileName); 
                $student->image = $fileName; 
            }

            //dd($student);
            $student->save();

            DB::commit();

            return redirect()->route('admin.student.index')->with('success', 'Student create successfully, Thank you.');
        
        } catch (\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
            //return back()->with('error', 'Something went wrong while creating student. Please try again.');
        }

    }

    public function edit($id){
        $student = Student::find($id);
        return view('backend.student.edit', compact('student'));
    }

    public function update(Request $request, $id){
        
        $student = Student::find($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:students,email,' . $student->id,
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
            'class'       => 'required|string|max:100',
            'section'     => 'required|string|max:50',
            'gender'      => 'required|in:male,female,other',
            'age'         => 'required|integer|max:999',
            'dob'         => 'required|date|before:today',
            'pre_school'  => 'required|string|max:255',
            'pre_class'   => 'required|string|max:255',
            'pre_section' => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try{

            if($student->user_id){
                $user = User::find($student->user_id);
                if($user){
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->role = 'student';
                    $user->save();
                }
                
            }

            $student->name = $request->name;
            $student->email = $request->email;
            $student->phone = $request->phone;
            $student->class = $request->class;
            $student->section = $request->section;
            $student->gender = $request->gender;
            $student->age = $request->age;
            $student->dob = $request->dob;
            $student->pre_school = $request->pre_school;
            $student->pre_class = $request->pre_class;
            $student->pre_section = $request->pre_section;

            if($request->hasFile('image')){ 
                @unlink(public_path("upload/students/".$student->image));
                $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
                request()->image->move(public_path('upload/students/'),$fileName); 
                $student->image = $fileName; 
            }

            //dd($student);
            $student->save();

            DB::commit();

            return redirect()->route('admin.student.index')->with('success', 'Student update successfully, Thank you.');
        
        } catch (\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
            //return back()->with('error', 'Something went wrong while creating student. Please try again.');
        }



    }


    public function delete($id){
        $student = Student::find($id);

        if(!$student){
            return redirect()->route('admin.student.index')->with('error', 'Student not found.');
        }

        if($student->image && file_exists(public_path("upload/students/".$student->image))){
            unlink(public_path("upload/students/".$student->image));
        }

        $student->delete();

        return redirect()->route('admin.student.index')->with('success', 'Student deleted successfully.');
    }


}
