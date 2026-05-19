<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffAuthController extends Controller
{
    public function showLogin()
    {
        return view('staff.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (
            $request->username === 'admin'
            &&
            $request->password === 'password123'
        ) {

            session([
                'staff_logged_in' => true,
            ]);

            return redirect()
                ->route('staff.dashboard');
        }

        return back()->withErrors([
            'login' => 'Invalid credentials.',
        ]);
    }

    public function logout()
    {
        session()->forget('staff_logged_in');

        return redirect()
            ->route('staff.login');
    }
}