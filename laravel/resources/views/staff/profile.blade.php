@extends('layouts.staff')

@section('content')

<div class="px-4 md:px-8 py-8">

    <div class="bg-white rounded-2xl shadow p-6 max-w-2xl">

        <h2 class="text-xl font-bold mb-6">
            My Profile
        </h2>

        <div class="mb-8 space-y-2 text-sm">
            <p>
                <strong>Name:</strong>
                {{ Auth::guard('staff')->user()->first_name }}
                {{ Auth::guard('staff')->user()->last_name }}
            </p>

            <p>
                <strong>Role:</strong>
                {{ ucfirst(Auth::guard('staff')->user()->role) }}
            </p>

            <p>
                <strong>Email Status:</strong>
                <span class="text-yellow-700">
                    Verification coming later
                </span>
            </p>
        </div>

        <h3 class="text-lg font-bold mb-4">
            Change Email
        </h3>

        <form
            method="POST"
            action="{{ route('staff.profile.email') }}"
            class="space-y-5 mb-10"
        >
            @csrf
            @method('PATCH')

            <input
                type="email"
                name="email"
                value="{{ Auth::guard('staff')->user()->email }}"
                class="w-full border rounded-lg px-4 py-3"
                required
            >

            <button
                class="bg-blue-700 hover:bg-blue-800 text-white px-5 py-3 rounded-lg font-semibold"
            >
                Update Email
            </button>
        </form>

        <hr class="my-8">

        <h3 class="text-lg font-bold mb-4">
            Change Password
        </h3>

        <form
            method="POST"
            action="{{ route('staff.profile.password') }}"
            class="space-y-5"
        >
            @csrf
            @method('PATCH')

            <input
                type="password"
                name="current_password"
                placeholder="Current Password"
                class="w-full border rounded-lg px-4 py-3"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="New Password"
                class="w-full border rounded-lg px-4 py-3"
                required
            >

            <input
                type="password"
                name="password_confirmation"
                placeholder="Confirm New Password"
                class="w-full border rounded-lg px-4 py-3"
                required
            >

            <button
                class="bg-green-800 hover:bg-green-900 text-white px-5 py-3 rounded-lg font-semibold"
            >
                Update Password
            </button>
        </form>

    </div>

</div>

@endsection