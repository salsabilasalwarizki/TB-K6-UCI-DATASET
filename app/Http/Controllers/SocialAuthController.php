<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // --- GOOGLE ---
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
            return redirect('/login')->withErrors(['msg' => 'Failed to login with Google.']);
        }
    }

    // --- GITHUB ---
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
            return redirect('/login')->withErrors(['msg' => 'Failed to login with GitHub.']);
        }
    }

    // --- LOGIC ---
    private function loginOrCreateUser($socialUser, $provider)
    {
        // 1. Cek apakah user sudah pernah login dengan provider ini sebelumnya
        $user = User::where("{$provider}_id", $socialUser->getId())->first();

        // 2. Jika tidak ada, cari berdasarkan email
        if (!$user) {
            $user = User::where('email', $socialUser->getEmail())->first();
        }

        // 3. Jika user tidak ada sama sekali, buat baru
        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt('12345678'), // Password random karena login via social
                'google_id' => $provider === 'google' ? $socialUser->getId() : null,
                'github_id' => $provider === 'github' ? $socialUser->getId() : null,
                'avatar' => $socialUser->getAvatar(),
                'institution' => 'Not Specified',
            ]);
        } else {
            // 4. Jika user sudah ada tapi belum punya google_id/github_id, update
            if (!$user->{"{$provider}_id"}) {
                $user->update([
                    "{$provider}_id" => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar() ?? $user->avatar,
                ]);
            }
        }

        // 5. Login user
        Auth::login($user);
        
        // 6. Redirect ke dashboard/profile
        return redirect()->intended('/profile');
    }
}