<?php

namespace App\Http\Controllers\backend\users\teacher;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index(Request $request){
        $search = $request->input('search');

        $teachers = User::where('role', 'teacher')
            ->where(function($query) use ($search){
            $query->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhereHas('expertCategory', function($q) use ($search){
                $q->where('name', 'like', "%{$search}%");
            });
        })->paginate(5);

        return view('backend.users.teacher.index', compact('teachers', 'search'));
    }

    public function create(){
        $categories = Category::where('status', 1)->get();
        return view('backend.users.teacher.create', compact('categories'));
    }
    public function store(Request $request){

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:6', 
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'expertise_category_id' => 'nullable|exists:categories,id', 
            'profession'  => 'required|string|max:255',
            'gender'      => 'required|in:male,female,other',
            'bio'         => 'nullable|string|max:255',
            'address'     => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try{
            $teacher = new User();
            $teacher->name = $request->name;
            $teacher->email = $request->email;
            $teacher->phone = $request->phone;
            $teacher->password = $request->password;
            $teacher->status = $request->status;
            $teacher->role = 'teacher';
            $teacher->expertise_category_id = $request->expertise_category_id;
            $teacher->profession = $request->profession;
            $teacher->gender = $request->gender;
            $teacher->bio = $request->bio;
            $teacher->address = $request->address;

            if($request->hasFile('image')){ 
                $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
                request()->image->move(public_path('upload/teacher/'),$fileName); 
                $teacher->image = $fileName; 
            }
            //dd($teacher);
            $teacher->save();
            DB::commit();
            return redirect()->route('admin.all-teacher')->with('success', 'Teacher Create Successfully. Thank you.');
        } catch (\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
        
    }

    public function edit($id){
        $teacher = User::find($id);
        $categories = Category::where('status', 1)->get();
        return view('backend.users.teacher.edit', compact('teacher', 'categories'));
    }

    public function update(Request $request, $id){

        $teacher = User::find($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$teacher->id,
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
        ]);

        DB::beginTransaction();

        try{
            
            $teacher->name = $request->name;
            $teacher->email = $request->email;
            $teacher->phone = $request->phone;
            $teacher->status = $request->status;
            if ($request->filled('password')) {
                $teacher->password = $request->password;
            }
            $teacher->expertise_category_id = $request->expertise_category_id;
            $teacher->profession = $request->profession;
            $teacher->gender = $request->gender;
            $teacher->bio = $request->bio;
            $teacher->address = $request->address;

            if($request->hasFile('image')){ 
                @unlink(public_path("upload/teacher/".$teacher->image));
                $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
                request()->image->move(public_path('upload/teacher/'),$fileName); 
                $teacher->image = $fileName; 
            }
            //dd($teacher);
            $teacher->save();
            DB::commit();
            return redirect()->route('admin.all-teacher')->with('success', 'Teacher Update Successfully. Thank you.');
        } catch (\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
        
    }

    public function delete($id){
        $teacher = User::find($id);

        if(!$teacher){
            return redirect()->route('admin.all-teacher')->with('error', 'Teacher not found.');
        }

        if($teacher->image && file_exists(public_path("upload/teacher/".$teacher->image))){
            unlink(public_path("upload/teacher/".$teacher->image));
        }

        $teacher->delete();

        return redirect()->route('admin.all-teacher')->with('success', 'Teacher deleted successfully.');
    }
}
