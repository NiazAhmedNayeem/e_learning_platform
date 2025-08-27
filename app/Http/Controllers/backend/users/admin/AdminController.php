<?php

namespace App\Http\Controllers\backend\users\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request){
        $search = $request->input('search');

        $admins = User::where('role', 'admin')
            ->where(function($query) use ($search){
            $query->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");
        })->paginate(5);

        return view('backend.users.admin.index', compact('admins', 'search'));
    }

    public function create(){
        return view('backend.users.admin.create');
    }
    public function store(Request $request){

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|',
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
            'image'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

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
            return redirect()->route('user.admin.index')->with('success', 'Admin Create Successfully. Thank you.');
        } catch (\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
        
    }

    public function edit($id){
        $admin = User::find($id);
        return view('backend.users.admin.edit', compact('admin'));
    }

    public function update(Request $request, $id){

        $admin = User::find($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$admin->id,
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
        ]);

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
            return redirect()->route('user.admin.index')->with('success', 'Admin Update Successfully. Thank you.');
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

        return redirect()->route('user.admin.index')->with('success', 'Admin deleted successfully.');
    }
    
}
