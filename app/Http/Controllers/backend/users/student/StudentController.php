<?php

namespace App\Http\Controllers\backend\users\student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
public function index(Request $request) {
        // $search = $request->input('search');

        // $students = User::where('role', 'student')
        //     ->where(function($query) use ($search){
        //     $query->where('name', 'like', "%{$search}%")
        //     ->orWhere('email', 'like', "%{$search}%")
        //     ->orWhere('unique_id', 'like', "%{$search}%");
        // })->paginate(10);

        if($request->ajax()){
            $data = User::where('role', 'student')->select('id', 'image', 'unique_id', 'name', 'email', 'phone', 'gender');
            return DataTables::of($data)->addIndexColumn()
            ->addColumn('image', function($row){
                
                if (!$row->image || $row->image == "N/A") {
                    $default = "https://ui-avatars.com/api/?name=" . urlencode($row->name) . "&size=160";
                    return '<img src="' . $default . '" width="50" height="50" class="rounded-circle" />';
                }

                $image = asset('public/upload/students/'.$row->image);
                return '<img src="' . $image . '" width="50" height="50" class="rounded-circle" />';
            })
            ->addColumn('action', function($row){
                
                $editUrl = route('admin.student.edit', $row->id);

                $btn = '<a href="'.$editUrl.'" class="edit btn btn-sm btn-primary me-1">Edit</a>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger deleteUser" data-id="'.$row->id.'">Delete</button>';
                return $btn;
            })->rawColumns(['image', 'action'])
            ->make(true);
        }

        return view('backend.users.student.index');
        // return view('backend.users.student.index', compact('students', 'search'));
    }

    public function create(){
        return view('backend.users.student.create');
    }

    public function store(Request $request){
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:6', 
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'profession'  => 'required|string|max:255',
            'gender'      => 'required|in:male,female,other',
            'bio'         => 'nullable|string|max:255',
            'address'     => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = $request->password;
            $user->role = 'student';
            $user->gender = $request->gender;
            $user->profession = $request->profession;
            $user->bio = $request->bio;
            $user->address = $request->address;
            $user->status = $request->status;

            ///Create unique id
            $today = date('Ymd');
            if ($user->role === 'student') {
                $count = User::where('role', 'student')
                            ->whereDate('created_at', today())
                            ->count() + 1;
                $number = str_pad($count, 3, '0', STR_PAD_LEFT);
                $unique_id = 'S' . $today . $number;
            }
            $user->unique_id = $unique_id;
            
            if($request->hasFile('image')){ 
                $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
                request()->image->move(public_path('upload/students/'),$fileName); 
                $user->image = $fileName; 
            }
            //dd($user);
            $user->save();

            DB::commit();

            return redirect()->route('admin.student.index')->with('success', 'Student create successfully, Thank you.');
        
        } catch (\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
            //return back()->with('error', 'Something went wrong while creating student. Please try again.');
        }

    }

    public function edit($id){
        $student = User::find($id);
        return view('backend.users.student.edit', compact('student'));
    }

    public function update(Request $request, $id){
        
        $user = User::find($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$user->id,
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
        ]);

        DB::beginTransaction();

        try{

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            if ($request->filled('password')) {
                $user->password = $request->password;
            }
            $user->gender = $request->gender;
            $user->profession = $request->profession;
            $user->bio = $request->bio;
            $user->address = $request->address;
            $user->status = $request->status;


            if($request->hasFile('image')){ 
                @unlink(public_path("upload/students/".$user->image));
                $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
                request()->image->move(public_path('upload/students/'),$fileName); 
                $user->image = $fileName; 
            }

            //dd($user);
            $user->save();

            DB::commit();

            return redirect()->route('admin.student.index')->with('success', 'Student update successfully, Thank you.');
        
        } catch (\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
            //return back()->with('error', 'Something went wrong while creating student. Please try again.');
        }



    }


    public function delete($id){
        $student = User::find($id);

        if(!$student){
            return redirect()->route('admin.student.index')->with('error', 'Student not found.');
        }

        if($student->image && file_exists(public_path("upload/students/".$student->image))){
            unlink(public_path("upload/students/".$student->image));
        }

        $student->delete();

        return response()->json(['success' => 'Student deleted successfully.']);
        // return redirect()->route('admin.student.index')->with('success', 'Student deleted successfully.');
    }
}

