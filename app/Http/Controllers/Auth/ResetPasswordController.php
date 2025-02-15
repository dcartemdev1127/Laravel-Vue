<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon; 
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    public function showForgotForm(): View {
        return view('auth.forgot_form');
    }

    public function showOtpForm(): View {
        return view('auth.otp');
    }

    /**
     * Where to redirect users after reSettings their password.
     *
     * @var string
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'passcode' => 'required'
        ]);
        $email = $request->input('email');
        $passcode = $request->input('passcode');
        $password_resets = DB::table('password_resets')->where(['email' => $email, 'token' => $passcode])->first();
        if($password_resets) {
            $createdTime = Carbon::parse($password_resets->created_at);
            $minutesDifference = intval($createdTime->diffInMinutes(Carbon::now()));
            if($minutesDifference > 15) {
                DB::table('password_resets')->where(['email' => $email, 'token' => $passcode])->delete();
                return response()->json([
                    'message' => 'Secure code was expired, try to send again'
                ], 500);
            } else {
                DB::table('password_resets')->where(['email' => $email, 'token' => $passcode])->delete();
                return response()->json([
                    'message' => 'Please reset your password'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Secure code is not correct'
            ], 422);
        }
    }

    public function sendResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users'
        ]);
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        if ($user) {
            try {
                $passcode = generateRandomPasscode();
                $userData = [
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'passcode' => $passcode
                ];
                DB::table('password_resets')->insert([
                    'email' => $email,
                    'token' => $passcode,
                    'created_at' => Carbon::now()
                ]);
                Mail::to($email)->send(new ResetPassword($userData));
                return response()->json([
                    'message' => 'Passcode was sent successfully, Confirm your mail box'
                ]);
            } catch (Exception) {
                return response()->json([
                    'message' => __('auth.failed')
                ], 500);
            }
        } else {
            return response()->json([
                'message' => __('auth.failed')
            ], 500);
        }
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', $email)->first();

        $hash_password = Hash::make($password);
        $user->password = $hash_password;
        $user->save();
        return response()->json([
            'message' => 'Password Reset Successfully now you can login.'
        ]);
    }
}
