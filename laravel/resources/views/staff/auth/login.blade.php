<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-6">

<div class="bg-white rounded-2xl shadow-xl p-10 w-full max-w-md">

    <div class="text-center mb-8">

        <img
            src="{{ asset('images/nigeria-mortgages-logo-green.png') }}"
            class="h-16 mx-auto mb-4"
        >

        <h1 class="text-2xl font-bold">
            Staff Login
        </h1>

    </div>

    @if($errors->any())

        <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl">
            {{ $errors->first() }}
        </div>

    @endif

    <form method="POST" action="{{ route('staff.login.submit') }}" class="space-y-5">

        @csrf

        <div>

            <label class="block text-sm font-semibold mb-2">
                Email
            </label>

            <input
                type="email"
                name="email"
                required
                class="w-full border border-gray-300 rounded-xl px-4 py-3"
            >

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                Password
            </label>

            <input
                type="password"
                name="password"
                required
                class="w-full border border-gray-300 rounded-xl px-4 py-3"
            >

        </div>

        <button
            class="w-full bg-green-800 hover:bg-green-900 text-white py-3 rounded-xl font-semibold"
        >
            Login
        </button>

    </form>

</div>

</body>
</html>