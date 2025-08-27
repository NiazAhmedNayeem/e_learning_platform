<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\TeacherRegisteredNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,teacher'],
        ]);
        //dd($request);

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
            $status = 2; //request for teacher
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'unique_id' => $unique_id,
            'status' => $status,
        ]);

        //dd($user);

        event(new Registered($user));


        // notify all admins for teacher 
        $admins = User::where('role', 'admin')->where('status', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new TeacherRegisteredNotification($user));
        }

        //Auth::login($user);

        //return redirect(route('dashboard', absolute: false));
        return redirect()->route('login')->with('success', 'Register successfully, now you can login');

    }
}
