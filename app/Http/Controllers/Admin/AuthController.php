<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends AbstractAdminLoginController
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    public function index()
    {
        if(Auth::check()){
            if(Auth::user()->role === "admin") return redirect($this->redirectTo);
            return view('admin.errors.need_permission');
        } else {
            return view('admin.login');
        }
    }
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        try {
            $user = User::where('email', $credentials['email'])->firstOrFail();
        } catch(ModelNotFoundException) {
            $this->sendFailedLoginResponse($request);
        }
        if(!password_verify($credentials['password'], $user->password)) {
            $this->sendFailedLoginResponse($request, $user);
        }
        if($user->role !== 'admin') {
            return response()->json([
                'errors' => true,
                'message' => "You don't have admin permission",
            ], 422);
        }
        return $this->sendLoginResponse($user, $request);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
