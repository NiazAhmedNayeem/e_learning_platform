<?php

namespace App\Http\Controllers\backend\users\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index(){
        return view('backend.users.admin.index');
    }


    public function data(Request $request)
    {
        $query = User::where('role', 'admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $admins = $query->orderBy('id', 'desc')->paginate(5);

        return response()->json($admins);
    }



    // public function create(){
    //     return view('backend.users.admin.create');
    // }


    
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:8',
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'      => 'required|in:0,1',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }


        DB::beginTransaction();

        try{
            $admin = new User();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->password = $request->password;
            $admin->status = $request->status;
            $admin->role = 'admin';

            $today = date('Ymd');
            if ($admin->role === 'admin') {
                $count = User::where('role', 'admin')
                            ->whereDate('created_at', today())
                            ->count() + 1;
                $number = str_pad($count, 3, '0', STR_PAD_LEFT);
                $unique_id = 'A' . $today . $number;
            }
            $admin->unique_id = $unique_id;


            if($request->hasFile('image')){ 
                $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
                request()->image->move(public_path('upload/admin/'),$fileName); 
                $admin->image = $fileName; 
            }
            //dd($admin);
            $admin->save();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Admin profile create successfully.',
                'admin' => $admin,
            ]);

            // return redirect()->route('user.admin.index')->with('success', 'Admin Create Successfully. Thank you.');
        } catch (\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
        
    }

    // public function edit($id){
    //     $admin = User::find($id);
    //     return view('backend.users.admin.edit', compact('admin'));
    // }


    public function edit($id)
    {
        $admin = User::find($id);

        if (!$admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Admin not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => $admin->phone,
                'status' => $admin->status,
                'image_show' => $admin->image_show,
            ]
        ]);
    }


    public function update(Request $request, $id){

        $admin = User::find($id);

        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$admin->id,
            'password'    => 'nullable|min:8',
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            // 'status'      => 'required|in:0,1',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try{
            
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;

            if ($request->filled('status')) {
                $admin->status = $request->status;
            }

            if ($request->filled('password')) {
                $admin->password = $request->password;
            }

            if($request->hasFile('image')){ 
                @unlink(public_path("upload/admin/".$admin->image));
                $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
                request()->image->move(public_path('upload/admin/'),$fileName); 
                $admin->image = $fileName; 
            }
            //dd($admin);
            $admin->save();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Admin profile update successfully.',
                'admin' => $admin,
            ]);
            // return redirect()->route('user.admin.index')->with('success', 'Admin Update Successfully. Thank you.');
        } catch (\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
        
    }

    public function delete($id){
        $admin = User::find($id);

        if(!$admin){
            return redirect()->route('user.admin.index')->with('error', 'Admin not found.');
        }

        if($admin->image && file_exists(public_path("upload/admin/".$admin->image))){
            unlink(public_path("upload/admin/".$admin->image));
        }

        $admin->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Admin profile delete successfully.',
        ]);
        // return redirect()->route('user.admin.index')->with('success', 'Admin deleted successfully.');
    }
    
}
