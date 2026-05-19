@extends('layouts.staff')

@section('content')

<div class="px-8 py-8">

    <div class="bg-white rounded-xl shadow p-6 mb-8">

        <h2 class="text-xl font-bold mb-5">
            Create Staff User
        </h2>

        <form method="POST" action="{{ route('staff.users.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4" autocomplete="off">
            @csrf

            <input name="first_name" placeholder="First Name" class="border rounded-lg px-4 py-3" required>

            <input name="last_name" placeholder="Last Name" class="border rounded-lg px-4 py-3" required>

            <input type="email" name="email" placeholder="Email" class="border rounded-lg px-4 py-3" required autocomplete="off">

            <input type="password" name="password" placeholder="Temporary Password" class="border rounded-lg px-4 py-3" required autocomplete="new-password">

            <select name="role" class="border rounded-lg px-4 py-3" required>
                <option value="support">Support</option>
                <option value="reviewer">Reviewer</option>
                <option value="manager">Manager</option>
                <option value="admin">Admin</option>
            </select>

            <button class="bg-green-800 hover:bg-green-900 text-white rounded-lg px-5 py-3 font-semibold">
                Create User
            </button>
        </form>

    </div>

    <div class="bg-white rounded-xl shadow overflow-x-auto">

        <table class="w-full min-w-[900px]">

            <thead class="bg-gray-100">
                <tr class="text-left text-sm text-gray-700">
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Last Login</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($staffUsers as $user)
                    <tr class="border-b text-sm">
                        <td class="px-4 py-3">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->email }}
                        </td>

                        <td class="px-4 py-3">
                            {{ ucfirst($user->role) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->last_login_at ? $user->last_login_at->format('M d, Y g:i A') : 'Never' }}
                        </td>

                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('staff.users.toggleStatus', $user->id) }}">
                                @csrf
                                @method('PATCH')

                                <button class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-lg text-xs">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            No staff users yet.
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