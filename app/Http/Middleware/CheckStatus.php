<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->status == 0) {

                $dashboardRoute = match($user->role) {
                    'admin' => 'admin.dashboard',
                    'teacher' => 'teacher.dashboard',
                    'student' => 'student.dashboard',
                    default => 'login'
                };

                if (!$request->routeIs($dashboardRoute)) {
                    return redirect()->route($dashboardRoute)
                        ->with('warning', 'Your account is inactive.');
                }
            }
            
            if ($user->status == 2) {

                $dashboardRoute = match($user->role) {
                    'admin' => 'admin.dashboard',
                    'teacher' => 'teacher.dashboard',
                    'student' => 'student.dashboard',
                    default => 'login'
                };

                if (!$request->routeIs($dashboardRoute)) {
                    return redirect()->route($dashboardRoute)
                        ->with('warning', 'Your account is Pending.');
                }
            }
            if ($user->status == 3) {

                $dashboardRoute = match($user->role) {
                    'admin' => 'admin.dashboard',
                    'teacher' => 'teacher.dashboard',
                    'student' => 'student.dashboard',
                    default => 'login'
                };

                if (!$request->routeIs($dashboardRoute)) {
                    return redirect()->route($dashboardRoute)
                        ->with('warning', 'Your account is Rejected.');
                }
            }
        }
        return $next($request);
    }
}
