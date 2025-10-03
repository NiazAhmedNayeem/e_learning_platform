<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function register(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users',
                'password' => 'required|min:8|confirmed',
                'role'     => 'required|in:teacher,student'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ], 422);
            }
            
            // Generate unique student ID
            $today = date('Ymd');

            if ($request->role === 'student') {
                $count = User::where('role', 'student')
                            ->whereDate('created_at', today())
                            ->count() + 1;
                $number = str_pad($count, 3, '0', STR_PAD_LEFT);
                $unique_id = 'S' . $today . $number;
                $status = 1; //Active
            } elseif ($request->role === 'teacher') {
                $count = User::where('role', 'teacher')
                            ->whereDate('created_at', today())
                            ->count() + 1;
                $number = str_pad($count, 3, '0', STR_PAD_LEFT);
                $unique_id = 'T' . $today . $number;
                $status = 2; //request for teacher (pending)
            }

            $user = User::create([
                'unique_id' => $unique_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => $status
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status'    => 'success',
                'message'   => 'User register successfully.',
                'token'     => $token,
                'user'      => $user,
            ], 201);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
        
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user || ! Hash::check($request->password, $user->password)){
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Credential',
            ], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successfully.',
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User logout successfully.',
        ], 200);
    }

}
