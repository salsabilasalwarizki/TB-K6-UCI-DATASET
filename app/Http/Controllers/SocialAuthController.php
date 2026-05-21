<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            return $this->loginOrCreateUser($socialUser, 'google');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['msg' => 'Failed to login with Google: ' . $e->getMessage()]);
        }
    }

    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        try {
            $socialUser = Socialite::driver('github')->user();
            return $this->loginOrCreateUser($socialUser, 'github');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['msg' => 'Failed to login with GitHub: ' . $e->getMessage()]);
        }
    }

    private function loginOrCreateUser($socialUser, $provider)
    {
        $user = User::where("{$provider}_id", $socialUser->getId())->first();

        if (!$user) {
            $user = User::where('email', $socialUser->getEmail())->first();
        }

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(24)),
                'email_verified_at' => now(),
                "{$provider}_id" => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'role' => 'user',
                'is_active' => true,
            ]);
        } else {
            if (!$user->{"{$provider}_id"}) {
                $user->update([
                    "{$provider}_id" => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar() ?? $user->avatar,
                ]);
            }
        }

            Auth::login($user, true);
    session()->regenerate(); // ← Pastikan ini ada!
    
    // Redirect ke profile atau home, bukan langsung ke admin
    return redirect()->intended('/profile');
    }
}