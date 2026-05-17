<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    // Login
    public function showLoginForm(Request $request)
    {
        $token = $request->cookie('api_token');

        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);

            if ($accessToken && $accessToken->tokenable) {
                return redirect()->route('dashboard');
            }
        }

        return view('admin.auth.login');
    }

    // Logout
    public function logout(Request $request)
    {
        $token = $request->cookie('api_token');

        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);
            $accessToken?->delete();
        }

        return redirect()->route('login')->withCookie(
            cookie()->forget('api_token')
        );
    }
}
