<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('api_token');
        if (! $token) {
            return redirect()->route('login');
        }

        // Date Validation check
        $accessToken = PersonalAccessToken::findToken($token);

        if (! $accessToken) {
            return redirect()->route('login')->withCookie(
                cookie()->forget('api_token')
            );
        }

        // User check
        $user = $accessToken->tokenable;
        if (! $user) {
            return redirect()->route('login')->withCookie(
                cookie()->forget('api_token')
            );
        }

        Auth::setUser($user);

        return $next($request);
    }
}
