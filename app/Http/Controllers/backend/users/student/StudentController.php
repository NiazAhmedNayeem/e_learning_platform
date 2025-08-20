<?php

namespace App\Http\Controllers\backend\users\student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request){
        $search = $request->input('search');

        $users = User::where('role', 'student')
            ->where(function($query) use ($search){
            $query->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhereHas('student', function($q) use ($search){
                $q->where('student_id', 'like', "%{$search}%");
            });
        })->paginate(10);

        return view('backend.users.student.index', compact('users', 'search'));
    }
}

