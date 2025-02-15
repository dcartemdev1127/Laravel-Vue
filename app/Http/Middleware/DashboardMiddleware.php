<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if(!$user) {
            $request->session()->put('redirect_url', $request->fullUrl());
            return redirect()->route('login');
        }
        if($user->role === 'admin') return redirect()->route('admin.dashboard');
        if($user->role === 'tutor' && !$user->tutor()->first()) {
            return redirect()->route('register');
        } else if($user->role === 'parent' && !$user->parent()->first()) {
            return redirect()->route('register');
        }
        if($user->role === 'tutor') {
            if(empty($user->package_id) || empty($user->annual_or_month)) {
                return redirect()->route('register.pricing');
            }
        }
        if(!$user->email_verified_at) {
            return redirect()->route('resend.verify.email');
        }
        if($request->session()->has('redirect_url')) {
            $redirectUrl = $request->session()->pull('redirect_url');
            return redirect($redirectUrl);
        }
        return $next($request);
    }
}
