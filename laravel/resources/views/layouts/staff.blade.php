<!-- /views/staff.blade 
    This is the main layout for all staff-related pages. It includes a sidebar for navigation and a header for page titles and user info.
    Individual pages will extend this layout and fill in the content section.
-->
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
                @php
                    $currentStaffUser = Auth::guard('staff')->user();
                    $currentStaffUserRole = $currentStaffUser?->role;
                    $currentStaffUserId = Auth::guard('staff')->id();
                    $canManageStaff = in_array($currentStaffUserRole, ['admin', 'manager']);
                    $isDashboard = request()->routeIs('staff.dashboard');
                    $isMyAssigned = (string) request('assigned_to') === (string) $currentStaffUserId;
                    $overdueTasksCount = $overdueTasksCount
                        ?? \App\Models\ApplicantTask::where('status', '!=', 'Completed')
                            ->whereDate('due_date', '<', now())
                            ->count();
                    $pendingPaymentsCount = $pendingPaymentsCount
                        ?? \App\Models\Payment::where('provider', 'manual_transfer')
                            ->where('status', 'pending_verification')
                            ->count();
                @endphp

                <a
                    href="{{ route('staff.dashboard') }}"
                    class="block px-4 py-3 rounded-lg {{ $isDashboard && !$isMyAssigned ? 'bg-green-700 text-white' : 'hover:bg-green-700 hover:text-white' }}"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('staff.dashboard') }}"
                    class="block px-4 py-3 rounded-lg {{ request()->routeIs('staff.applications.*') ? 'bg-green-700 text-white' : 'hover:bg-green-700 hover:text-white' }}"
                >
                    Applications
                </a>

                <a
                    href="{{ route('staff.dashboard', ['assigned_to' => $currentStaffUserId]) }}"
                    class="block px-4 py-3 rounded-lg {{ $isMyAssigned ? 'bg-green-700 text-white' : 'hover:bg-green-700 hover:text-white' }}"
                >
                    My Assigned
                </a>

                <a
                    href="{{ route('staff.tasks.index') }}"
                    class="block px-4 py-3 rounded-lg {{ request()->routeIs('staff.tasks.*') ? 'bg-green-700 text-white' : 'hover:bg-green-700 hover:text-white' }}"
                >
                    Tasks / Follow Ups

                    @if(($overdueTasksCount ?? 0) > 0)
                        <span class="ml-2 bg-red-600 text-white text-xs px-2 py-1 rounded-full">
                            {{ $overdueTasksCount }}
                        </span>
                    @endif
                </a>

                @if($canManageStaff)
                    <a
                        href="{{ route('staff.users.index') }}"
                        class="block px-4 py-3 rounded-lg {{ request()->routeIs('staff.users.*') ? 'bg-green-700 text-white' : 'hover:bg-green-700 hover:text-white' }}"
                    >
                        Staff Users
                    </a>
                @endif

                @if($canManageStaff)
                    <a
                        href="{{ route('staff.applications.exportCsv') }}"
                        class="block px-4 py-3 rounded-lg {{ request()->routeIs('staff.applications.exportCsv') ? 'bg-green-700 text-white' : 'hover:bg-green-700 hover:text-white' }}"
                    >
                        Reports / Export
                    </a>
                @endif

                <a
                    href="{{ route('staff.profile.edit') }}"
                    class="block px-4 py-3 rounded-lg {{ request()->routeIs('staff.profile.*') ? 'bg-green-700 text-white' : 'hover:bg-green-700 hover:text-white' }}"
                >
                    My Profile
                </a>

                @if($canManageStaff)
                    <a
                        href="{{ route('staff.payments.index') }}"
                        class="block px-4 py-3 rounded-lg {{ request()->routeIs('staff.payments.*') ? 'bg-green-700 text-white' : 'hover:bg-green-700 hover:text-white' }}"
                    >
                        Payments

                        @if(($pendingPaymentsCount ?? 0) > 0)
                            <span class="ml-2 bg-yellow-400 text-white text-xs px-2 py-1 rounded-full">
                                {{ $pendingPaymentsCount }}
                            </span>
                        @endif
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

                <div>
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
                        type="button"
                        class="relative bg-gray-100 hover:bg-gray-200 rounded-full p-3"
                    >
                        🔔
                    </button>
                    
                    
                    {{-- Staff Name / Role --}}
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-semibold text-gray-800">
                            {{ Auth::guard('staff')->user()->first_name ?? 'Staff' }}
                        </div>

                        <div class="text-xs text-gray-500">
                            {{ ucfirst(Auth::guard('staff')->user()->role ?? 'staff') }}
                        </div>
                    </div>

                    {{-- Avatar Dropdown --}}
                    <div class="relative">

                        <button
                            type="button"
                            onclick="document.getElementById('avatarDropdown').classList.toggle('hidden')"
                            class="h-10 w-10 rounded-full bg-green-800 text-white flex items-center justify-center font-bold"
                        >
                            {{ strtoupper(substr(Auth::guard('staff')->user()->first_name ?? 'S', 0, 1)) }}
                        </button>

                        <div
                            id="avatarDropdown"
                            class="hidden absolute right-0 mt-3 w-48 bg-white border rounded-xl shadow-lg z-[99999]"
                        >
                            <a
                                href="{{ route('staff.profile.edit') }}"
                                class="block px-5 py-3 text-sm hover:bg-gray-100"
                            >
                                My Profile
                            </a>

                            <form method="POST" action="{{ route('staff.logout') }}">
                                @csrf

                                <button
                                    class="w-full text-left px-5 py-3 text-sm text-red-600 hover:bg-red-50"
                                >
                                    Logout
                                </button>
                            </form>
                        </div>

                    </div>

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

        @yield('content')

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
