<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

abstract class AbstractLoginController extends Controller {
    use AuthenticatesUsers;
    protected AuthManager $auth;

    protected int $lockoutTime;
    protected int $maxLoginAttempts;

    protected string $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->lockoutTime = config('auth.lockout.time');
        $this->maxLoginAttempts = config('auth.lockout.attempts');
        $this->auth = Container::getInstance()->make(AuthManager::class);
    }

    protected function sendLoginResponse(User $user, Request $request, $remember = false): JsonResponse
    {
        $request->session()->remove('auth_confirmation_token');
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $this->auth->guard()->login($user, $remember);

        return new JsonResponse([
            'complete' => true,
            'intended' => $this->redirectPath(),
            'user' => $user->toVueObject()
        ]);
    }

    protected function sendFailedLoginResponse(Request $request, Authenticatable $user = null, string $message = null)
    {
        $this->incrementLoginAttempts($request);
        throw new Exception(__('auth.failed'));
    }

    public function sendResponse($result, $message) {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404) {
        $response = [
            'success' => false,
            'message' => $error
        ];

        if(!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        };

        return response()->json($response, $code);
    }
}