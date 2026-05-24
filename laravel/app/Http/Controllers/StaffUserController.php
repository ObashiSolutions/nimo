<?php

namespace App\Http\Controllers;

use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffUserController extends Controller
{
    public function index()
    {
        $currentStaffUser = Auth::guard('staff')->user();

        $staffUsersQuery = StaffUser::latest();

        if ($currentStaffUser?->role === 'manager') {
            $staffUsersQuery->whereIn('role', ['reviewer', 'support']);
        }

        $staffUsers = $staffUsersQuery->paginate(50);

        return view('staff.users.index', compact('staffUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:staff_users,email',
            'password'   => 'required|string|min:8',
            'role'       => 'required|in:admin,manager,reviewer,support',
        ]);

        $currentStaffUser = Auth::guard('staff')->user();

        if (
            $currentStaffUser->role === 'manager'
            && in_array($validated['role'], ['admin', 'manager'])
        ) {
            return back()
                ->withErrors('Managers can only create Reviewer or Support users.')
                ->withInput();
        }

        StaffUser::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role'       => $validated['role'],
            'is_active'  => true,
        ]);

        return back()->with('success_message', 'Staff user created successfully.');
    }

    public function toggleStatus(StaffUser $staffUser)
    {
        $currentStaffUser = Auth::guard('staff')->user();

        if ((int) $currentStaffUser->id === (int) $staffUser->id) {
            return back()->withErrors('You cannot deactivate your own account.');
        }

        if (
            $currentStaffUser->role === 'manager'
            && in_array($staffUser->role, ['admin', 'manager'])
        ) {
            return back()->withErrors('Managers cannot activate or deactivate Admin or Manager users.');
        }

        $staffUser->update([
            'is_active' => !$staffUser->is_active,
        ]);

        return back()->with('success_message', 'Staff user status updated.');
    }
}
