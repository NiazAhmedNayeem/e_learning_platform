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

        $user = auth()->user();

        $message = '';
        $type = 'success';

        // Role-based message set 
        if ($user->role == 'admin') {
            if ($user->status == 1) {
                $message = 'Hello ' . $user->name . '! Welcome to Admin Dashboard.';
                $type = 'success';
            } elseif ($user->status == 0) {
                $message = 'Hello ' . $user->name . '! Welcome to Admin Dashboard. Your account is inactive.';
                $type = 'warning';
            }
        } elseif ($user->role == 'teacher') {
            if ($user->status == 1) {
                $message = 'Hello ' . $user->name . '! Welcome to Teacher Dashboard.';
                $type = 'success';
            } elseif ($user->status == 2) {
                $message = 'Hello ' . $user->name . '! Your account is pending.';
                $type = 'warning';
            }
        } elseif ($user->role == 'student') {
            if ($user->status == 1) {
                $message = 'Hello ' . $user->name . '! Welcome to Student Dashboard.';
                $type = 'success';
            }
        } else {
            return redirect()->route('login')->with('warning', 'Something is wrong');
        }

        // Redirect back to the previously intended page, or use fallback route if none exists
        return redirect()->intended($this->defaultDashboardRoute($user))
            ->with($type, $message);
    }

    /**
     * // Determine the fallback route based on the user's role
     */
    private function defaultDashboardRoute($user)
    {
        if ($user->role == 'admin') {
            return route('admin.dashboard');
        } elseif ($user->role == 'teacher') {
            return route('teacher.dashboard');
        } elseif ($user->role == 'student') {
            return route('student.dashboard');
        } else {
            return route('login'); // generic fallback
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
