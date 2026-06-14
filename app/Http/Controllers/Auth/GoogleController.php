<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Unable to authenticate with Google. Please try again.',
            ]);
        }

        // Check if a user with this google_id already exists
        $user = User::query()->where('google_id', $googleUser->getId())->first();

        if ($user) {
            Auth::login($user);
            return redirect()->intended(route('home'));
        }

        // Check if a user with this email exists (without google_id linked)
        $user = User::query()->where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Link the Google ID to the existing account
            $user->update([
                'google_id' => $googleUser->getId(),
            ]);
        } else {
            // Register a new user
            $user = User::query()->create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'role' => User::ROLE_STUDENT,
                'password' => null, // Password is null for social login
            ]);
        }

        Auth::login($user);

        return redirect()->route('home');
    }
}
