@extends('layouts.staff')

@section('content')

@php
    $canAssignManagers = $currentStaffUser?->role === 'admin';
@endphp

<div class="px-8 py-8">

    <div class="bg-white rounded-xl shadow p-6 mb-8">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
            <div>
                <h2 class="text-xl font-bold">
                    Create Staff User
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Managers can create and manage Reviewer or Support users under them.
                </p>
            </div>

            @if($currentStaffUser?->role === 'admin')
                <div class="flex gap-3">
                    <a
                        href="{{ route('staff.users.index') }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold {{ !$showDeleted ? 'bg-green-800 text-white' : 'bg-gray-100 text-gray-700' }}"
                    >
                        Current Users
                    </a>

                    <a
                        href="{{ route('staff.users.index', ['view' => 'deleted']) }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold {{ $showDeleted ? 'bg-green-800 text-white' : 'bg-gray-100 text-gray-700' }}"
                    >
                        Deleted Users
                    </a>
                </div>
            @endif
        </div>

        @unless($showDeleted)
            <form method="POST" action="{{ route('staff.users.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4" autocomplete="off">
                @csrf

                <input name="first_name" placeholder="First Name" class="border rounded-lg px-4 py-3" required>

                <input name="last_name" placeholder="Last Name" class="border rounded-lg px-4 py-3" required>

                <input type="email" name="email" placeholder="Email" class="border rounded-lg px-4 py-3" required autocomplete="off">

                <input type="password" name="password" placeholder="Temporary Password" class="border rounded-lg px-4 py-3" required autocomplete="new-password">

                <select name="role" class="border rounded-lg px-4 py-3" required>
                    <option value="">Select Role</option>

                    @if($currentStaffUser?->role === 'admin')
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                    @endif

                    <option value="reviewer">Reviewer</option>
                    <option value="support">Support</option>
                </select>

                @if($canAssignManagers)
                    <select name="managed_by_staff_user_id" class="border rounded-lg px-4 py-3">
                        <option value="">No Manager</option>

                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}">
                                {{ $manager->first_name }} {{ $manager->last_name }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <button class="bg-green-800 hover:bg-green-900 text-white rounded-lg px-5 py-3 font-semibold">
                    Create User
                </button>
            </form>
        @endunless

    </div>

    <div class="bg-white rounded-xl shadow overflow-x-auto">

        <table class="w-full min-w-[1000px]">

            <thead class="bg-gray-100">
                <tr class="text-left text-sm text-gray-700">
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Manager</th>
                    <th class="px-4 py-3">Last Login</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($staffUsers as $user)
                    <tr class="border-b text-sm hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold">
                            <a href="{{ route('staff.users.show', $user->id) }}" class="text-green-800 underline">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </a>
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->email }}
                        </td>

                        <td class="px-4 py-3">
                            {{ ucfirst($user->role) }}
                        </td>

                        <td class="px-4 py-3">
                            @if($user->trashed())
                                Deleted
                            @else
                                {{ ucfirst(str_replace('_', ' ', $user->account_status ?? 'active')) }}
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->manager ? $user->manager->first_name . ' ' . $user->manager->last_name : 'None' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->last_login_at ? $user->last_login_at->format('M d, Y g:i A') : 'Never' }}
                        </td>

                        <td class="px-4 py-3">
                            <a
                                href="{{ route('staff.users.show', $user->id) }}"
                                class="inline-block bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-lg text-xs"
                            >
                                Open
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            No staff users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

    <div class="mt-6">
        {{ $staffUsers->links() }}
    </div>

</div>

@endsection
