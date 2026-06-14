@extends('layouts.staff')

@section('title', 'Staff User')

@section('content')
    @php
        $isAdmin = $currentStaffUser->role === 'admin';
        $roleOptions = $isAdmin ? ['admin', 'manager', 'reviewer', 'support'] : ['reviewer', 'support'];
        $statusOptions = $isAdmin ? ['active', 'deactivated', 'suspended', 'archived'] : ['active', 'deactivated', 'suspended'];
    @endphp

    <div class="space-y-6">
        <div>
            <a href="{{ route('staff.users.index') }}" class="text-sm font-medium text-blue-700 hover:text-blue-900">Back to staff users</a>
            <h1 class="mt-2 text-2xl font-semibold text-slate-900">{{ $staffUser->first_name }} {{ $staffUser->last_name }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $staffUser->email }}</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Record</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div>
                        <dt class="font-medium text-slate-500">Role</dt>
                        <dd class="mt-1 text-slate-900">{{ ucfirst($staffUser->role) }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-slate-500">Account status</dt>
                        <dd class="mt-1 text-slate-900">{{ ucfirst($staffUser->account_status) }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-slate-500">Manager</dt>
                        <dd class="mt-1 text-slate-900">
                            {{ $staffUser->manager ? $staffUser->manager->first_name . ' ' . $staffUser->manager->last_name : 'None' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="font-medium text-slate-500">Last login</dt>
                        <dd class="mt-1 text-slate-900">{{ optional($staffUser->last_login_at)->format('M j, Y g:i A') ?? 'Never' }}</dd>
                    </div>
                    @if ($staffUser->trashed())
                        <div>
                            <dt class="font-medium text-slate-500">Deleted</dt>
                            <dd class="mt-1 text-red-700">{{ optional($staffUser->deleted_at)->format('M j, Y g:i A') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="space-y-6 xl:col-span-2">
                @unless ($staffUser->trashed())
                    <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
                        <h2 class="text-lg font-semibold text-slate-900">Access</h2>

                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <form method="POST" action="{{ route('staff.users.role.update', $staffUser) }}">
                                @csrf
                                @method('PATCH')
                                <label for="role" class="block text-sm font-medium text-slate-700">Role</label>
                                <div class="mt-1 flex gap-2">
                                    <select id="role" name="role" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @foreach ($roleOptions as $role)
                                            <option value="{{ $role }}" @selected($staffUser->role === $role)>{{ ucfirst($role) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save</button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('staff.users.status.update', $staffUser) }}">
                                @csrf
                                @method('PATCH')
                                <label for="account_status" class="block text-sm font-medium text-slate-700">Status</label>
                                <div class="mt-1 flex gap-2">
                                    <select id="account_status" name="account_status" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @foreach ($statusOptions as $status)
                                            <option value="{{ $status }}" @selected($staffUser->account_status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save</button>
                                </div>
                            </form>
                        </div>

                        @if ($isAdmin)
                            <form method="POST" action="{{ route('staff.users.manager.update', $staffUser) }}" class="mt-4">
                                @csrf
                                @method('PATCH')
                                <label for="managed_by_staff_user_id" class="block text-sm font-medium text-slate-700">Managed by</label>
                                <div class="mt-1 flex gap-2">
                                    <select id="managed_by_staff_user_id" name="managed_by_staff_user_id" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">No manager</option>
                                        @foreach ($managers as $manager)
                                            <option value="{{ $manager->id }}" @selected((int) $staffUser->managed_by_staff_user_id === (int) $manager->id)>
                                                {{ $manager->first_name }} {{ $manager->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save</button>
                                </div>
                            </form>
                        @endif
                    </div>

                    <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
                        <h2 class="text-lg font-semibold text-slate-900">Password Help</h2>
                        <p class="mt-1 text-sm text-slate-500">Send help to this staff user's login email.</p>

                        <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                            <form method="POST" action="{{ route('staff.users.password-reset-link', $staffUser) }}">
                                @csrf
                                <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Send reset link</button>
                            </form>

                            <form method="POST" action="{{ route('staff.users.temporary-password', $staffUser) }}" onsubmit="return confirm('Generate and email a new temporary password?');">
                                @csrf
                                <button type="submit" class="rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">Send temporary password</button>
                            </form>
                        </div>
                    </div>
                @endunless

                @if ($isAdmin)
                    <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
                        <h2 class="text-lg font-semibold text-slate-900">Admin Actions</h2>

                        @if ($staffUser->trashed())
                            <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                                <form method="POST" action="{{ route('staff.users.restore', $staffUser->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Restore user</button>
                                </form>
                                <form method="POST" action="{{ route('staff.users.force-delete', $staffUser->id) }}" onsubmit="return confirm('Permanently delete this staff user? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Permanently delete</button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('staff.users.destroy', $staffUser) }}" class="mt-4" onsubmit="return confirm('Delete this staff user? You can restore them later from the deleted list.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete user</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
