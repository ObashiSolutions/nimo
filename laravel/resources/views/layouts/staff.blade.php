<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Staff Dashboard' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside
        id="staffSidebar"
        class="relative w-52 min-w-[13rem] md:w-72 md:min-w-[18rem] bg-green-900 text-white flex flex-col transition-all duration-300 overflow-hidden"
    >
        <button
            id="sidebarToggle"
            type="button"
            class="fixed left-52 md:left-[17.2rem] bottom-24 z-[9999] bg-green-800 hover:bg-green-700 text-white w-8 h-16 rounded-r-lg shadow font-bold transition-all duration-300"
        >
            ‹
        </button>

        <div id="sidebarInner">

            <div class="px-6 py-6 border-b border-green-800">
                <img
                    src="{{ asset('images/nigeria-mortgages-logo-green.png') }}"
                    alt="Logo"
                    class="h-auto w-40 object-contain"
                >

                <div class="mt-4">
                    <h1 class="text-lg font-bold leading-tight">
                        Nigeria Mortgages
                    </h1>

                    <p class="text-xs text-green-200">
                        Staff Dashboard
                    </p>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 text-sm">

                <a
                    href="{{ route('staff.dashboard') }}"
                    class="block px-4 py-3 rounded-lg {{ request()->routeIs('staff.dashboard') ? 'bg-green-800' : 'hover:bg-green-800' }}"
                >
                    Dashboard
                </a>

                <a href="{{ route('staff.dashboard') }}" class="block px-4 py-3 rounded-lg hover:bg-green-800">
                    Applications
                </a>

                <a
                    href="{{ route('staff.tasks.index') }}"
                    class="block px-4 py-3 rounded-lg {{ request()->routeIs('staff.tasks.*') ? 'bg-green-800' : 'hover:bg-green-800' }}"
                >
                    Tasks / Follow Ups

                    @php
                        $overdueTasksCount =
                            \App\Models\ApplicantTask::where('status', '!=', 'Completed')
                                ->whereDate('due_date', '<', now())
                                ->count();
                    @endphp

                    @if($overdueTasksCount > 0)

                        <span class="ml-2 inline-flex items-center justify-center bg-red-600 text-white text-xs font-bold rounded-full px-2 py-1">

                            {{ $overdueTasksCount }}

                        </span>

                    @endif

                </a>

                <a
                    href="{{ route('staff.applications.exportCsv') }}"
                    class="block px-4 py-3 rounded-lg {{ request()->routeIs('staff.applications.exportCsv') ? 'bg-green-800' : 'hover:bg-green-800' }}"
                >
                    Reports / Export
                </a>

                @php
                    $staffRole = Auth::guard('staff')->user()?->role;
                @endphp

                @if(in_array($staffRole, ['admin', 'manager']))
                <a
                    href="{{ route('staff.users.index') }}"
                    class="block px-4 py-3 rounded-lg {{ request()->routeIs('staff.users.*') ? 'bg-green-800' : 'hover:bg-green-800' }}"
                >
                    Staff Users
                </a>
                @endif

            </nav>

            <div class="p-4 border-t border-green-800">
                <form method="POST" action="{{ route('staff.logout') }}">
                    @csrf

                    <button
                        class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold"
                    >
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </aside>

    {{-- MAIN --}}
    <main class="flex-1 overflow-hidden">

        <header class="bg-white border-b px-8 py-5">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-fit">
                    
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $pageTitle ?? 'Staff Area' }}
                    </h2>

                    <p class="text-sm text-gray-500">
                        {{ $pageSubtitle ?? 'Nigeria Mortgages internal operations' }}
                    </p>

                </div>

                <div class="flex items-center gap-4">

                    {{-- Notification Bell --}}
                    <button
                        class="relative bg-gray-100 hover:bg-gray-200 transition p-3 rounded-full"
                    >

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-gray-700"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                                a6.002 6.002 0 00-4-5.659V5
                                a2 2 0 10-4 0v.341C7.67 6.165
                                6 8.388 6 11v3.159c0 .538-.214
                                1.055-.595 1.436L4 17h5m6
                                0v1a3 3 0 11-6 0v-1m6 0H9"
                            />
                        </svg>

                        @php
                            $newApplicantsCount =
                                \App\Models\Applicant::whereDate('created_at', today())
                                    ->count();
                        @endphp

                        @if($newApplicantsCount > 0)

                            <span
                                class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"
                            >
                                {{ $newApplicantsCount }}
                            </span>

                        @endif

                    </button>

                    {{-- Internal Access --}}
                    <div class="text-sm text-gray-500 whitespace-nowrap">
                        {{ Auth::guard('staff')->user()->first_name ?? 'Staff' }}
                        ·
                        {{ ucfirst(Auth::guard('staff')->user()->role ?? 'staff') }}
                    </div>

                </div>

            </div>


            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">

                <div class="bg-green-100 border border-green-200 rounded-xl px-5 py-4">

                    <p class="text-xs uppercase tracking-wide text-green-700 font-semibold">
                        Applications Today
                    </p>

                    <p class="text-2xl font-bold text-green-900 mt-2">

                        {{ \App\Models\Applicant::whereDate('created_at', today())->count() }}

                    </p>

                </div>

                <div class="bg-yellow-100 border border-yellow-200 rounded-xl px-5 py-4">

                    <p class="text-xs uppercase tracking-wide text-yellow-700 font-semibold">
                        Pending Verification
                    </p>

                    <p class="text-2xl font-bold text-yellow-900 mt-2">

                        {{ \App\Models\Applicant::where('payment_status', 'Pending Verification')->count() }}

                    </p>

                </div>

                <div class="bg-blue-100 border border-blue-200 rounded-xl px-5 py-4">

                    <p class="text-xs uppercase tracking-wide text-blue-700 font-semibold">
                        In Review
                    </p>

                    <p class="text-2xl font-bold text-blue-900 mt-2">

                        {{ \App\Models\Applicant::where('application_status', 'In Review')->count() }}

                    </p>

                </div>

                <div class="bg-red-100 border border-red-200 rounded-xl px-5 py-4">

                    <p class="text-xs uppercase tracking-wide text-red-700 font-semibold">
                        Overdue Tasks
                    </p>

                    <p class="text-2xl font-bold text-red-900 mt-2">

                        {{ \App\Models\ApplicantTask::where('status', '!=', 'Completed')
                            ->whereDate('due_date', '<', now())
                            ->count()
                        }}

                    </p>

                </div>

            </div>
        </header>

        @if(session('success_message'))
            <div class="px-8 pt-6">
                <div class="bg-green-100 border border-green-300 text-green-800 px-5 py-4 rounded-xl">
                    {{ session('success_message') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="px-8 pt-6">
                <div class="bg-red-100 border border-red-300 text-red-800 px-5 py-4 rounded-xl">
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        <div class="overflow-x-hidden">
            @yield('content')
        </div>

    </main>

</div>

<script>
    const sidebar = document.getElementById('staffSidebar');
    const sidebarInner = document.getElementById('sidebarInner');
    const toggle = document.getElementById('sidebarToggle');

    toggle.addEventListener('click', function () {
        const collapsed = sidebar.classList.contains('w-3');

        if (collapsed) {
            sidebar.classList.remove('w-3', 'min-w-[0.75rem]');
            sidebar.classList.add('w-52', 'min-w-[13rem]', 'md:w-72', 'md:min-w-[18rem]');

            sidebarInner.classList.remove('hidden', 'pointer-events-none');

            toggle.innerHTML = '‹';
            toggle.classList.remove('left-3');
            toggle.classList.add('left-52', 'md:left-[17.2rem]');
        } else {
            sidebar.classList.remove('w-52', 'min-w-[13rem]', 'md:w-72', 'md:min-w-[18rem]');
            sidebar.classList.add('w-3', 'min-w-[0.75rem]');

            sidebarInner.classList.add('hidden', 'pointer-events-none');

            toggle.innerHTML = '›';
            toggle.classList.remove('left-52', 'md:left-[17.2rem]');
            toggle.classList.add('left-3');
        }
    });
</script>

</body>
</html>