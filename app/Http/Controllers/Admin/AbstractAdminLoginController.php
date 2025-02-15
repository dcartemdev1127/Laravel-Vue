<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

abstract class AbstractAdminLoginController extends Controller {
    use AuthenticatesUsers;
    protected AuthManager $auth;

    protected int $lockoutTime;
    protected int $maxLoginAttempts;

    protected string $redirectTo = '/admin';

    public function __construct()
    {
        $this->lockoutTime = config('auth.lockout.time');
        $this->maxLoginAttempts = config('auth.lockout.attempts');
        $this->auth = Container::getInstance()->make(AuthManager::class);
    }

    protected function sendLoginResponse(User $user, Request $request): JsonResponse
    {
        $request->session()->remove('auth_confirmation_token');
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $this->auth->guard()->login($user);

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
}