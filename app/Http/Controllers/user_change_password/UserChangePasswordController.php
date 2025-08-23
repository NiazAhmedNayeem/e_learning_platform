<?php

namespace App\Http\Controllers\user_change_password;

use App\Http\Controllers\Controller;
use App\Models\PasswordChangeOtp;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordChangeOtpMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserChangePasswordController extends Controller
{
    public function showChangeForm(){
        $user = auth()->user();
        return view('user_change_password.change_password',compact('user'));
    }

    public function requestChange(Request $request){
        $request->validate([
            'current_password' => ['required'],
            'new_password'     => ['required','confirmed', Rules\Password::defaults()],
        ]);

        $user = $request->user();

        // current password match check
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        
        PasswordChangeOtp::where('user_id', $user->id)->delete();

        // 6-digit OTP
        $otp = (string) random_int(100000, 999999);

        // new record
        $record = PasswordChangeOtp::create([
            'user_id'          => $user->id,
            'otp_hash'         => Hash::make($otp),                      // OTP hash
            'new_password_hash'=> Hash::make($request->new_password),    // new password hash
            'expires_at'       => now()->addMinutes(60),                 // 60 min
            'attempts'         => 0,
        ]);

        
        Mail::to($user->email)->send(new PasswordChangeOtpMail($user->name, $otp));

        return redirect()->route('password.change.otp')
            ->with('success', 'An OTP has been sent to your email. It will expire in 60 minutes.');
    }

    // STEP 3: OTP form
    public function showOtpForm()
    {
        $user = auth()->user();
        return view('user_change_password.otp_verification', compact('user'))->with('success', 'You send an OTP to your email for changing password. Thank you.');
    }

    // STEP 4: OTP verify -> password update
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required','digits:6'],
        ]);

        $user = $request->user();

        $record = PasswordChangeOtp::where('user_id', $user->id)->first();

        if (! $record) {
            return back()->withErrors(['otp' => 'No OTP request found. Please request again.']);
        }

        // Expired?
        if (now()->greaterThan($record->expires_at)) {
            $record->delete();
            return back()->withErrors(['otp' => 'OTP expired. Please request a new one.']);
        }

        // Attempt limit (optional): max 5 tries
        if ($record->attempts >= 5) {
            $record->delete();
            return back()->withErrors(['otp' => 'Too many attempts. Please request a new OTP.']);
        }

        // Check OTP
        $record->increment('attempts');
        if (! Hash::check($request->otp, $record->otp_hash)) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        // OTP valid → update password
        $user->password = $record->new_password_hash; // already hashed
        $user->setRememberToken(Str::random(60));
        $user->save();

        // OTP record cleanup
        $record->delete();

        
        // auth()->logoutOtherDevices($request->new_password); 

        if($user->role == 'teacher'){
            return redirect()->route('teacher.profile')->with('success', 'Password updated successfully!');
        }elseif($user->role == 'student'){
            return redirect()->route('student.profile')->with('success', 'Password updated successfully!');
        }
    }
        

    // Optional: resend OTP 
    public function resendOtp(Request $request)
    {
        $user = $request->user();

        $record = PasswordChangeOtp::where('user_id', $user->id)->first();

        if (! $record) {
            return back()->withErrors(['otp' => 'No pending OTP. Please start the process again.']);
        }

        // new OTP
        $otp = (string) random_int(100000, 999999);

        $record->update([
            'otp_hash'   => Hash::make($otp),
            'expires_at' => now()->addMinutes(60),
            'attempts'   => 0,
        ]);

        Mail::to($user->email)->send(new PasswordChangeOtpMail($user->name, $otp));

        return back()->with('success', 'A new OTP has been sent to your email.');
    }
}