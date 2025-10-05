<?php

namespace App\Http\Controllers\api\profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiStudentProfileController extends Controller
{
    public function profile(){
        $userId = auth()->user()->id;
        $student = User::find($userId);
        if(!$student){
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found.',
            ], 404);
        }
        if($student->role !== 'student'){
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }
        return response()->json([
            'status' => 'success',
            'message' => "Welcome to {$student->name}",
            'student' => $student,
        ], 200);
    }

    public function update(Request $request){

        $userId = auth()->user()->id;

        $student = User::find($userId);

        if(!$student){
            return response()->json([
                'status' => 'error',
                'message' => 'student not found.',
            ], 404);
        }
        if($student->role !== 'student'){
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        try{
            $validator = Validator::make($request->all(), [
                'name'                  => 'nullable|string|max:100',
                'email'                 => 'nullable|email|unique:users,email,'.$student->id,
                'phone'                 => 'nullable|string|max:11',
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
                @unlink(public_path("upload/students/".$student->image));
                $image = $request->file('image');
                $imageName = time().uniqid().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('upload/students/'), $imageName);
                $student->image = $imageName;
            }

            $data = [
                'name'                  => $request->name ?? $student->name,
                'phone'                 => $request->phone ?? $student->phone,
                'profession'            => $request->profession ?? $student->profession,
                'gender'                => $request->gender ?? $student->gender,
                'bio'                   => $request->bio ?? $student->bio,
                'address'               => $request->address ?? $student->address,
                'image'                 => $student->image,
            ];

            if(!empty($request->email)){
                $data['email'] = $request->email;
            }

            $student->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile update successfully.',
                'student' => $student,
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
