<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = auth()->user();

        // Inactive users
        // if ($user->status == 0) {
        //     return redirect()->route('inactive.dashboard')->with('error', 'Your account is inactive.');
        // }

        // Admin route
        if ($role === 'admin') {
            if ($user->role === 'admin') {
                return $next($request);
            }
            if ($user->role === 'teacher') {
                return redirect()->route('teacher.dashboard')->with('error', 'You cannot access admin panel.');
            }
            if ($user->role === 'student') {
                return redirect()->route('student.dashboard')->with('error', 'You cannot access admin panel.');
            }
        }

        // Teacher route
        if ($role === 'teacher') {
            if ($user->role === 'teacher') {
                return $next($request);
            }
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'You cannot access teacher panel.');
            }
            if ($user->role === 'student') {
                return redirect()->route('student.dashboard')->with('error', 'You cannot access teacher panel.');
            }
        }

        // Student route
        if ($role === 'student') {
            if ($user->role === 'student') {
                return $next($request);
            }
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'You cannot access student panel.');
            }
            if ($user->role === 'teacher') {
                return redirect()->route('teacher.dashboard')->with('error', 'You cannot access student panel.');
            }
        }

        // Default fallback
        return redirect()->route('login')->with('error', 'You are not authorized to access this page.');
    }
}
