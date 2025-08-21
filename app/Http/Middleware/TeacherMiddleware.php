<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check()){
            $user = auth()->user();

            
            if($user->role == 'teacher' && $user->status == 1){
                
                if($request->is(AdminMiddleware::class)){ 
                    return redirect()->route('teacher.dashboard')
                                     ->with('error', 'You cannot access admin panel.');
                }
                return $next($request);
            }

            //inactive teachers
            return redirect('/inactive.dashboard')->with('error', 'You are not authorized to access this page.');
        }

        // guest
        return redirect('/login')->with('error', 'Please login first.');
    }
}
