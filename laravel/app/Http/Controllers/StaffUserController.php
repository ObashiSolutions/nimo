<?php

namespace App\Http\Controllers;

use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffUserController extends Controller
{
    public function index()
    {
        $staffUsers = StaffUser::latest()->paginate(50);

        return view('staff.users.index', compact('staffUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:staff_users,email',
            'password'   => 'required|string|min:8',
            'role'       => 'required|in:admin,manager,reviewer,support',
        ]);

        StaffUser::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'is_active'  => true,
        ]);

        return back()->with('success_message', 'Staff user created successfully.');
    }

    public function toggleStatus(StaffUser $staffUser)
    {
        $staffUser->update([
            'is_active' => !$staffUser->is_active,
        ]);

        return back()->with('success_message', 'Staff user status updated.');
    }
}