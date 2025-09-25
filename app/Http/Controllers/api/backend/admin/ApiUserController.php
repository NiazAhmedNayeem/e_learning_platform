<?php

namespace App\Http\Controllers\api\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiUserController extends Controller
{
    public function index(){
        $users = User::all();
        // $users = User::where('role', 'admin')->where('is_super', 1)->get();
        // $users = User::where('role', 'admin')->get();
        // $users = User::where('role', 'teacher')->get();
        // $users = User::where('role', 'student')->get();
        return response()->json($users);
    }

    public function store(Request $request){

        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users',
                'phone'    => 'nullable|string|max:20',
                'password' => 'required|min:8',
                'role'     => 'required|in:admin,teacher,student',
            ]);

            
            $today = date('Ymd');
            
            if($request->role === 'admin'){

                $count = User::where('role', 'admin')
                            ->whereDate('created_at', today())
                            ->count() + 1;
                $number = str_pad($count, 3, '0', STR_PAD_LEFT);
                $unique_id = 'A' . $today . $number;
                $user_unique_id = $unique_id;

            }elseif($request->role === 'teacher'){

                $count = User::where('role', 'teacher')
                            ->whereDate('created_at', today())
                            ->count() + 1;
                $number = str_pad($count, 3, '0', STR_PAD_LEFT);
                $unique_id = 'T' . $today . $number;
                $user_unique_id = $unique_id;

            }elseif($request->role === 'student'){

                $count = User::where('role', 'student')
                            ->whereDate('created_at', today())
                            ->count() + 1;
                $number = str_pad($count, 3, '0', STR_PAD_LEFT);
                $unique_id = 'S' . $today . $number;
                $user_unique_id = $unique_id;
            }


            $user = User::create([
                'unique_id' => $user_unique_id,
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'password'  => bcrypt($request->password),
                'role'      => $request->role ?? 'student',
                'image'     => $request->image ?? null,
                'expertise_category_id' => $request->expertise_category_id,
                'profession'=> $request->profession,
                'gender'    => $request->gender,
                'bio'       => $request->bio,
                'address'   => $request->address,
                'is_super'  => $request->is_super ?? 0,
                'status'    => $request->status ?? 1,
            ]);

            return response()->json([
                'message' => 'User Create successfully.',
                'user' => $user,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id){
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.',
            ], 404);
        }
        return response()->json($user);
    }

    public function update(Request $request, $id){

        $user = User::find($id);

        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.',
            ], 404);
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|min:8',
        ]);



        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'image'     => $request->image ?? null,
            'expertise_category_id' => $request->expertise_category_id,
            'profession'=> $request->profession,
            'gender'    => $request->gender,
            'bio'       => $request->bio,
            'address'   => $request->address,
            'status'    => $request->status ?? 1,
        ];


        if($request->filled('password')){
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'User update successfully.',
            'user' => $user,
        ], 201);
    }

    
    public function delete($id){
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);
        }

        $roleFolder = ['admin' => 'admin', 'teacher' => 'teacher', 'student' => 'students'];

        $folder = $roleFolder[$user->role] ?? $user->role;

        if($user->image){
            $filePath = public_path("upload/{$folder}/".$user->image);
            if(file_exists($filePath)){
                unlink($filePath);
            }
        }


        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User delete successfully.'
        ], 200);
    }


    
}
