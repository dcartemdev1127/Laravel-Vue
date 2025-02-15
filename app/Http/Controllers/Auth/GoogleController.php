<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends AbstractLoginController
{
    public function redirectToGoogle (Request $request) {
        $request->validate([
            'role' => 'required',
            'type' => 'required'
        ]);
        $request->session()->put('google', json_encode(['role' => $request->role, 'type' => $request->type]));
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request) {
        try {
            if(!$request->session()->has('google')) {
                $request->session()->put('error', 'Google Auth failed');
                return redirect()->route('register');
            }
            $data = $request->session()->pull('google');
            $data = json_decode($data);
            $type = $data->type;
            if($type === 'login') {
                $googleUser = Socialite::driver('google')->user();
                $email = $googleUser->getEmail();
                $user = User::where('email', $email)->first();
                if(!$user) {
                    $request->session()->put('error', 'User not registered');
                    return redirect()->route('login');
                }
                $this->sendLoginResponse($user, $request);
                return redirect()->route('dashboard.index');
            } else {
                $googleUser = Socialite::driver('google')->user();
                $email = $googleUser->getEmail();
                $user = User::where('email', $email)->first();
                if($user) {
                    $this->sendLoginResponse($user, $request);
                    return redirect()->route('dashboard.index');
                } else {
                    $role = $data->role;
                    $name = $googleUser->getName();
                    $name = explode(" ", $name);
                    $firstname = $name[0];
                    $lastname = isset($name[1])? $name[1] : ' ';
                    $newUser = new User([
                        'role' => $role,
                        'email' => $email,
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'password' => Hash::make('default')
                    ]);
                    $avatar = $googleUser->getAvatar();
                    if(!empty($avatar)) $newUser->avatar = $avatar;
                    $newUser->email_verified_at = now();
                    $newUser->save();
                    $this->sendLoginResponse($newUser, $request);
                    return redirect()->route('dashboard.index');
                }
            }
        } catch(Exception) {
            $request->session()->put('error', 'Google Login failed');
            return redirect()->route('register');
        }
    }
}
