<?php

namespace App\Http\Controllers\api\profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiTeacherProfileController extends Controller
{
    public function profile(){
        $userId = auth()->user()->id;
        $teacher = User::find($userId);
        if(!$teacher){
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher not found.',
            ], 404);
        }
        if($teacher->role !== 'teacher'){
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }
        return response()->json([
            'status' => 'success',
            'message' => "Welcome to {$teacher->name}",
            'teacher' => $teacher,
        ], 200);
    }

    public function update(Request $request){

        $userId = auth()->user()->id;

        $teacher = User::find($userId);

        if(!$teacher){
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher not found.',
            ], 404);
        }
        if($teacher->role !== 'teacher'){
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        try{
            $validator = Validator::make($request->all(), [
                'name'                  => 'nullable|string|max:100',
                'email'                 => 'nullable|email|unique:users,email,'.$teacher->id,
                'phone'                 => 'nullable|string|max:11',
                'expertise_category_id' => 'nullable|exists:categories,id',
                'profession'            => 'nullable|string|max:255',
                'gender'                => 'nullable|in:male,female',
                'bio'                   => 'nullable|string|max:255',
                'address'               => 'nullable|string|max:255',
                'image'                 => 'nullable|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }

            if($request->hasFile('image')){
                @unlink(public_path("upload/teacher/".$teacher->image));
                $image = $request->file('image');
                $imageName = time().uniqid().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('upload/teacher/'), $imageName);
                $teacher->image = $imageName;
            }

            $data = [
                'name'                  => $request->name ?? $teacher->name,
                'phone'                 => $request->phone ?? $teacher->phone,
                'expertise_category_id' => $request->expertise_category_id ?? $teacher->expertise_category_id,
                'profession'            => $request->profession ?? $teacher->profession,
                'gender'                => $request->gender ?? $teacher->gender,
                'bio'                   => $request->bio ?? $teacher->bio,
                'address'               => $request->address ?? $teacher->address,
                'image'                 => $teacher->image,
            ];

            if(!empty($request->email)){
                $data['email'] = $request->email;
            }

            $teacher->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile update successfully.',
                'teacher' => $teacher,
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
