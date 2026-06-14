<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Staff Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h1 class="text-2xl font-semibold text-slate-900">Reset password</h1>
            <p class="mt-2 text-sm text-slate-500">Set a new staff password for {{ $staffUser->email }}.</p>

            @if ($errors->any())
                <div class="mt-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ request()->fullUrl() }}" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">New password</label>
                    <input id="password" name="password" type="password" required class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <button type="submit" class="w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save password</button>
            </form>
        </div>
    </main>
</body>
</html>
