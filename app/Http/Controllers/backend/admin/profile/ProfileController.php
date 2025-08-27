<?php

namespace App\Http\Controllers\backend\admin\profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile(){
        $admin = auth()->user();
        return view('backend.admin_profile.index', compact('admin'));
    }

    public function edit(){
        $admin = auth()->user();
        return view('backend.admin_profile.edit', compact('admin'));
    }

    public function update(Request $request){

        $admin = auth()->user();

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$admin->id,
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->gender = $request->gender;
        $admin->profession = $request->profession;
        $admin->address = $request->address;
        $admin->bio = $request->bio;

        if($request->hasFile('image')){ 
            @unlink(public_path("upload/admin/".$admin->image));
            $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
            request()->image->move(public_path('upload/admin/'),$fileName); 
            $admin->image = $fileName; 
        }
        //dd($admin);
        $admin->save();

        return redirect()->route('admin.profile')->with('success', 'Your profile is updated.');

    }
}
