<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        //return redirect()->intended(route('dashboard', absolute: false));

        ///For Admin User
        if(auth()->user()->role == 'admin'){
            if(auth()->user()->status == 1){
                return redirect()->route('admin.dashboard')
                ->with('success', 'Hello '. auth()->user()->name .'! Welcome to Admin Dashboard.');
            }
            if(auth()->user()->status == 0){
                return redirect()->route('admin.dashboard')
                ->with('warning', 'Hello '. auth()->user()->name .'! Welcome to Admin Dashboard. Your account is inactive.');
            }
        }
        ///For Teacher User
        elseif(auth()->user()->role == 'teacher'){
            if(auth()->user()->status == 1){
                return redirect()->route('teacher.dashboard')
                ->with('success', 'Hello '. auth()->user()->name .'! Welcome to Teacher Dashboard.');
            }elseif(auth()->user()->status == 2){
                return redirect()->route('teacher.dashboard')
                ->with('warning', 'Hello '. auth()->user()->name .'! Your account is pending.');
            }
        }

        ///For Student User
        elseif(auth()->user()->role == 'student'){
            if(auth()->user()->status == 1){
                return redirect()->route('student.dashboard')
                ->with('success', 'Hello '. auth()->user()->name .'! Welcome to Student Dashboard.');
            }
        }
        else {
            //return redirect()->route('dashboard');
            return redirect()->route('login')
            ->with('warning', 'Something is wrong');
        }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
