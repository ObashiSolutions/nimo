<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow rounded-2xl p-10 w-full max-w-md">

        <h1 class="text-3xl font-bold mb-6 text-center">
            Staff Login
        </h1>

        @if($errors->any())

            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">

                {{ $errors->first() }}

            </div>

        @endif

        <form
            method="POST"
            action="{{ route('staff.login.submit') }}"
            class="space-y-5"
        >
            @csrf

            <div>

                <label class="block mb-2 font-semibold">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    class="w-full border rounded-lg px-4 py-3"
                >

            </div>

            <div>

                <label class="block mb-2 font-semibold">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-lg px-4 py-3"
                >

            </div>

            <button
                class="w-full bg-green-700 hover:bg-green-800 text-white py-3 rounded-lg font-semibold"
            >
                Login
            </button>

        </form>

    </div>

</body>
</html>