<?php

namespace App\Http\Controllers;

use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StaffUserController extends Controller
{
    private const ROLES = ['admin', 'manager', 'reviewer', 'support'];
    private const STATUSES = ['active', 'deactivated', 'suspended', 'archived'];

    public function index(Request $request)
    {
        $currentStaffUser = Auth::guard('staff')->user();
        $showDeleted = $currentStaffUser?->role === 'admin' && $request->query('view') === 'deleted';

        $staffUsersQuery = StaffUser::query()
            ->with('manager:id,first_name,last_name,email')
            ->latest();

        if ($showDeleted) {
            $staffUsersQuery->onlyTrashed();
        }

        if ($currentStaffUser?->role === 'manager') {
            $staffUsersQuery
                ->where('managed_by_staff_user_id', $currentStaffUser->id)
                ->whereIn('role', ['reviewer', 'support']);
        }

        $staffUsers = $staffUsersQuery->paginate(50)->withQueryString();
        $managers = $this->availableManagers();

        return view('staff.users.index', compact(
            'staffUsers',
            'currentStaffUser',
            'managers',
            'showDeleted'
        ));
    }

    public function show($staffUser)
    {
        $staffUser = StaffUser::withTrashed()
            ->with('manager:id,first_name,last_name,email')
            ->findOrFail($staffUser);

        $this->authorizeManage($staffUser);

        return view('staff.users.show', [
            'staffUser' => $staffUser,
            'currentStaffUser' => Auth::guard('staff')->user(),
            'managers' => $this->availableManagers(),
            'roles' => self::ROLES,
            'statuses' => self::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        $currentStaffUser = Auth::guard('staff')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff_users,email',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in($this->rolesCurrentUserCanAssign())],
            'managed_by_staff_user_id' => ['nullable', 'exists:staff_users,id'],
        ]);

        $managerId = $currentStaffUser->role === 'manager'
            ? $currentStaffUser->id
            : ($validated['managed_by_staff_user_id'] ?? null);

        StaffUser::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'account_status' => 'active',
            'managed_by_staff_user_id' => $managerId,
            'is_active' => true,
            'must_change_password' => true,
            'status_changed_at' => now(),
        ]);

        return back()->with('success_message', 'Staff user created successfully.');
    }

    public function updateRole(Request $request, StaffUser $staffUser)
    {
        $this->authorizeManage($staffUser);

        $validated = $request->validate([
            'role' => ['required', Rule::in($this->rolesCurrentUserCanAssign($staffUser))],
        ]);

        $staffUser->update([
            'role' => $validated['role'],
        ]);

        return back()->with('success_message', 'Staff role updated.');
    }

    public function updateManager(Request $request, StaffUser $staffUser)
    {
        $currentStaffUser = Auth::guard('staff')->user();

        if ($currentStaffUser?->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'managed_by_staff_user_id' => ['nullable', 'exists:staff_users,id'],
        ]);

        $staffUser->update([
            'managed_by_staff_user_id' => $validated['managed_by_staff_user_id'] ?? null,
        ]);

        return back()->with('success_message', 'Staff manager updated.');
    }

    public function updateStatus(Request $request, StaffUser $staffUser)
    {
        $this->authorizeManage($staffUser);

        $validated = $request->validate([
            'account_status' => ['required', Rule::in($this->statusesCurrentUserCanAssign())],
        ]);

        if ((int) Auth::guard('staff')->id() === (int) $staffUser->id && $validated['account_status'] !== 'active') {
            return back()->withErrors('You cannot make your own account unavailable.');
        }

        $staffUser->update([
            'account_status' => $validated['account_status'],
            'is_active' => $validated['account_status'] === 'active',
            'status_changed_at' => now(),
        ]);

        return back()->with('success_message', 'Staff status updated.');
    }

    public function sendResetLink(StaffUser $staffUser)
    {
        $this->authorizeManage($staffUser);

        $resetUrl = URL::temporarySignedRoute(
            'staff.password.reset',
            now()->addHours(2),
            ['staffUser' => $staffUser->id]
        );

        Mail::raw(
            "A password reset was requested for your Nigeria Mortgages staff account.\n\nOpen this link within 2 hours to set a new password:\n{$resetUrl}",
            fn ($message) => $message
                ->to($staffUser->email)
                ->subject('Reset your staff password')
        );

        return back()->with('success_message', 'Password reset link sent.');
    }

    public function sendTemporaryPassword(StaffUser $staffUser)
    {
        $this->authorizeManage($staffUser);

        $temporaryPassword = Str::password(14);

        $staffUser->update([
            'password' => Hash::make($temporaryPassword),
            'must_change_password' => true,
        ]);

        Mail::raw(
            "A temporary password was created for your Nigeria Mortgages staff account.\n\nTemporary password: {$temporaryPassword}\n\nPlease sign in and change it right away.",
            fn ($message) => $message
                ->to($staffUser->email)
                ->subject('Temporary staff password')
        );

        return back()->with('success_message', 'Temporary password sent.');
    }

    public function destroy(StaffUser $staffUser)
    {
        $this->authorizeAdmin();

        if ((int) Auth::guard('staff')->id() === (int) $staffUser->id) {
            return back()->withErrors('You cannot delete your own account.');
        }

        $staffUser->delete();

        return redirect()
            ->route('staff.users.index')
            ->with('success_message', 'Staff user moved to deleted users.');
    }

    public function restore($staffUser)
    {
        $this->authorizeAdmin();

        $staffUser = StaffUser::onlyTrashed()->findOrFail($staffUser);
        $staffUser->restore();

        return back()->with('success_message', 'Staff user restored.');
    }

    public function forceDelete($staffUser)
    {
        $this->authorizeAdmin();

        $staffUser = StaffUser::onlyTrashed()->findOrFail($staffUser);
        $staffUser->forceDelete();

        return redirect()
            ->route('staff.users.index', ['view' => 'deleted'])
            ->with('success_message', 'Staff user permanently deleted.');
    }

    public function showPasswordReset(Request $request, StaffUser $staffUser)
    {
        abort_unless($request->hasValidSignature(), 403);

        return view('staff.auth.reset-password', compact('staffUser'));
    }

    public function updatePasswordFromReset(Request $request, StaffUser $staffUser)
    {
        abort_unless($request->hasValidSignature(), 403);

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $staffUser->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);

        return redirect()
            ->route('staff.login')
            ->with('success_message', 'Password updated. You can sign in now.');
    }

    private function authorizeManage(StaffUser $staffUser): void
    {
        $currentStaffUser = Auth::guard('staff')->user();

        if ($currentStaffUser?->role === 'admin') {
            return;
        }

        if (
            ! $staffUser->trashed()
            && $currentStaffUser?->role === 'manager'
            && (int) $staffUser->managed_by_staff_user_id === (int) $currentStaffUser->id
            && in_array($staffUser->role, ['reviewer', 'support'], true)
        ) {
            return;
        }

        abort(403);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::guard('staff')->user()?->role === 'admin', 403);
    }

    private function rolesCurrentUserCanAssign(?StaffUser $staffUser = null): array
    {
        $currentStaffUser = Auth::guard('staff')->user();

        if ($currentStaffUser?->role === 'admin') {
            return self::ROLES;
        }

        return ['reviewer', 'support'];
    }

    private function statusesCurrentUserCanAssign(): array
    {
        $currentStaffUser = Auth::guard('staff')->user();

        if ($currentStaffUser?->role === 'admin') {
            return self::STATUSES;
        }

        return ['active', 'deactivated', 'suspended'];
    }

    private function availableManagers()
    {
        return StaffUser::where('role', 'manager')
            ->where('account_status', 'active')
            ->orderByRaw('LOWER(first_name)')
            ->orderByRaw('LOWER(last_name)')
            ->get(['id', 'first_name', 'last_name', 'email']);
    }
}
