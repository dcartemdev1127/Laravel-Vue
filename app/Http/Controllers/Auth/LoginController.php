<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class LoginController extends AbstractLoginController
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    public function index()
    {
        return redirect('/');
    }
    public function showLogin(Request $request)
    {
        $error['message'] = null;
        if($request->session()->has('error')) $error['message'] = $request->session()->pull('error');
        if(Auth::check()){
            return redirect()->route('dashboard.index');
        } else {
            return view('auth.login', compact('error'));
        }
    }
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            // 'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        $remember = $request->has('keepmelogged') ? $request->input('keepmelogged') : false;
        $credentials = $request->only('email', 'password');
        try {
            $user = User::where('email', $credentials['email'])->firstOrFail();
        } catch(ModelNotFoundException) {
            return $this->sendFailedLoginResponse($request);
        }
        if(!password_verify($credentials['password'], $user->password)) {
            return $this->sendFailedLoginResponse($request, $user);
        }
        if(!$user->status === 'blocked') {
            return response()->json([
                'errors' => [
                    'email' => 'The provided email is blocked in our records.',
                ],
                'message' => "Your account is Blocked.",
            ], 422);
        }
        return $this->sendLoginResponse($user, $request, $remember);
    }

    public function loginUser() {
        $credentials = request(['username', 'password']);

        if (! $token = JWTAuth::attempt($credentials)) {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
  
        $success = $this->respondWithToken($token);
   
        return $this->sendResponse($success, 'User login successfully.');
    }

    public function refresh() {
        $success = $this->respondWithToken(JWTAuth::refresh());
        return $this->sendResponse($success, 'Refresh token return successfully.');
    }

    protected function respondWithToken($token) {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ];
    }

}
