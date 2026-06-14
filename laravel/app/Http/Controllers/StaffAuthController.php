<?php

namespace App\Http\Controllers;

use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffAuthController extends Controller
{
    public function showLogin()
    {
        return view('staff.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = StaffUser::where('email', $credentials['email'])->first();

        if (!$user || !$user->isAvailableForLogin()) {

            return back()->withErrors([
                'email' => 'Invalid credentials or unavailable account.',
            ]);
        }

        if (
            Auth::guard('staff')->attempt(
                $credentials,
                $request->boolean('remember')
            )
        ) {

            $user->update([
                'last_login_at' => now(),
            ]);

            $request->session()->regenerate();

            if ($user->must_change_password) {
                return redirect()
                    ->route('staff.profile.edit')
                    ->withErrors('Please change your temporary password before continuing.');
            }

            return redirect()->route('staff.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('staff.login');
    }
}
