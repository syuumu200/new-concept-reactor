<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MicrosoftController extends Controller
{
    public function login(Request $request)
    {
        if ($request->missing("code")) {
            $url = Socialite::driver('microsoft')->redirect();
            return $request->hasHeader('X-Inertia') ?
                Inertia::location($url) :
                redirect()->away($url);
        }

        $user = Socialite::driver("microsoft")->user();

        $user = User::updateOrCreate(
            [
                'id' => $user->id,
            ],
            [
                'email' => $user->mail,
                'username' => $user->displayName,
                'avatar' => $user->avatar
            ]
        );

        Auth::login($user);
        return redirect($request->session()->pull('before_auth_url', '/'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        return redirect()->route("index");
    }
}
