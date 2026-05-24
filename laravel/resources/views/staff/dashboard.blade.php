@extends('layouts.staff')

@section('content')



                <!-- SIDEBAR -->
                

                    <!-- TOP BAR -->
                    <div class="bg-white border-b px-8 py-5">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">
                                    Applications
                                </h2>

                                <p class="text-sm text-gray-500">
                                    Manage mortgage pre-approval submissions
                                </p>
                            </div>


                            <!-- FILTERS -->
                            <div class="flex flex-wrap items-center gap-3">
                                <form method="GET" class="flex flex-wrap items-center gap-3">
                                    <input
                                        type="search"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="Search applicants..."
                                        class="border rounded-lg px-4 py-2 w-72"
                                        autocomplete="off"
                                    >

                                    <button
                                        type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold"
                                    >
                                        Search
                                    </button>
                                    

                                    <!-- Status Filter -->
                                    <select
                                        name="status"
                                        onchange="this.form.submit()"
                                        class="border rounded-lg px-4 py-2"
                                    >
                                        <option value="">All Statuses</option>

                                        <option value="Pending Payment"
                                            {{ request('status') == 'Pending Payment' ? 'selected' : '' }}>
                                            Pending Payment
                                        </option>

                                        <option value="Pending Verification"
                                            {{ request('status') == 'Pending Verification' ? 'selected' : '' }}>
                                            Pending Verification
                                        </option>

                                        <option value="In Review"
                                            {{ request('status') == 'In Review' ? 'selected' : '' }}>
                                            In Review
                                        </option>

                                        <option value="Approved"
                                            {{ request('status') == 'Approved' ? 'selected' : '' }}>
                                            Approved
                                        </option>

                                        <option value="Rejected"
                                            {{ request('status') == 'Rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>

                                        <option value="Closed"
                                            {{ request('status') == 'Closed' ? 'selected' : '' }}>
                                            Closed
                                        </option>
                                    </select>


                                    @if(in_array(Auth::guard('staff')->user()?->role, ['admin', 'manager']))
                                    <!-- Assigned Staff Filter -->
                                    <select
                                        name="assigned_to"
                                        class="border rounded-lg px-4 py-3"
                                    >
                                        <option value="">All Assigned Staff</option>

                                        @foreach($assignableStaffUsers as $staffUser)
                                            <option
                                                value="{{ $staffUser->id }}"
                                                @selected((int) request('assigned_to') === (int) $staffUser->id)
                                            >
                                                {{ $staffUser->first_name }} {{ $staffUser->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @endif

                                    <!-- Sort Filter -->
                                    <select
                                        name="sort"
                                        onchange="this.form.submit()"
                                        class="border rounded-lg px-4 py-2"
                                    >
                                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                                            Newest First
                                        </option>
                                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                            Oldest First
                                        </option>
                                        <option value="highest_property" {{ request('sort') == 'highest_property' ? 'selected' : '' }}>
                                            Highest Property Cost
                                        </option>
                                        <option value="lowest_property" {{ request('sort') == 'lowest_property' ? 'selected' : '' }}>
                                            Lowest Property Cost
                                        </option>
                                    </select>
                                    
                                    <a
                                        href="{{ route('staff.dashboard', ['assigned_to' => Auth::guard('staff')->id()]) }}"
                                        class="inline-block bg-green-700 hover:bg-green-800 text-white px-5 py-3 rounded-lg font-semibold"
                                    >
                                        My Assigned Applications
                                    </a>

                                    <form method="GET">

                                        <select
                                            name="per_page"
                                            onchange="this.form.submit()"
                                            class="border rounded-lg px-4 py-2"
                                        >
                                            <option value="200" {{ request('per_page', 200) == 200 ? 'selected' : '' }}>
                                                200 Per Page
                                            </option>

                                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>
                                                100 Per Page
                                            </option>

                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>
                                                50 Per Page
                                            </option>

                                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>
                                                20 Per Page
                                            </option>

                                        </select>

                                    </form>

                                    @if(in_array(Auth::guard('staff')->user()?->role, ['admin', 'manager']))

                                        <a
                                            href="{{ route('staff.applications.exportCsv') }}"
                                            class="bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-lg font-semibold"
                                        >
                                            Export CSV
                                        </a>

                                    @endif
                                </form>   
                            </div>
                        </div>
                    </div>

                    <!-- NOTIFICATIONS -->
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


                    <!-- STATS -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 px-8 py-6">
                        <div class="bg-white rounded-xl shadow p-5">
                            <p class="text-sm text-gray-500">
                                Total Applications
                            </p>

                            <h3 class="text-3xl font-bold mt-2">
                                {{ $applicants->count() }}
                            </h3>
                        </div>

                        <div class="bg-white rounded-xl shadow p-5">
                            <p class="text-sm text-gray-500">
                                Pending Payment
                            </p>

                            <h3 class="text-3xl font-bold mt-2">
                                {{ $applicants->where('payment_status', 'Unpaid')->count() }}
                            </h3>
                        </div>

                        <div class="bg-white rounded-xl shadow p-5">
                            <p class="text-sm text-gray-500">
                                Pending Verification
                            </p>

                            <h3 class="text-3xl font-bold mt-2">
                                {{ $applicants->where('payment_status', 'Pending Verification')->count() }}
                            </h3>
                        </div>
                        
                        <div class="bg-white rounded-xl shadow p-5">
                            <p class="text-sm text-gray-500">
                                Overdue Tasks
                            </p>

                            <h3 class="text-3xl font-bold mt-2 text-red-600">

                                {{
                                    \App\Models\ApplicantTask::where('status', '!=', 'Completed')
                                        ->whereDate('due_date', '<', now())
                                        ->count()
                                }}

                            </h3>
                        </div>

                        <div class="bg-white rounded-xl shadow p-5">
                            <p class="text-sm text-gray-500">
                                Approved
                            </p>

                            <h3 class="text-3xl font-bold mt-2">
                                {{ $applicants->where('application_status', 'Approved')->count() }}
                            </h3>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div class="px-8 pb-8">
                        <div class="bg-white rounded-xl shadow overflow-auto">
                            <div class="overflow-x-auto">
                            <table class="min-w-[2200px] w-full">
                                <thead class="sticky top-0 z-20 bg-gray-100 border-b ">
                                    <tr class="text-left text-sm text-gray-700">
                                        <th class="px-4 py-3 whitespace-nowrap">
                                            <input
                                                id="selectAllApplicants"
                                                type="checkbox"
                                                class="h-4 w-4 rounded border-gray-300"
                                                aria-label="Select all visible applicants"
                                            >
                                        </th>

                                        <th class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('staff.dashboard', array_merge(request()->query(), ['sort' => 'reference_id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Reference ID
                                            </a>
                                        </th>

                                        <th class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('staff.dashboard', array_merge(request()->query(), ['sort' => 'first_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                First Name
                                            </a>
                                        </th>

                                        <th class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('staff.dashboard', array_merge(request()->query(), ['sort' => 'last_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Last Name
                                            </a>
                                        </th>

                                        <th class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('staff.dashboard', array_merge(request()->query(), ['sort' => 'email', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Email
                                            </a>
                                        </th>
                                        <th class="px-4 py-3 whitespace-nowrap">
                                            Assigned To
                                        </th>
                                        <th class="px-4 py-3 whitespace-nowrap">Phone</th>
                                        <th class="px-4 py-3 whitespace-nowrap">Address</th>
                                        <th class="px-4 py-3 whitespace-nowrap">City</th>
                                        <th class="px-4 py-3 whitespace-nowrap">State</th>
                                        <th class="px-4 py-3 whitespace-nowrap">Company</th>
                                        <th class="px-4 py-3 whitespace-nowrap">Occupation</th>
                                        <th class="px-4 py-3 whitespace-nowrap">Years</th>
                                        <th class="px-4 py-3 whitespace-nowrap">Estate</th>
                                        <th class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('staff.dashboard', array_merge(request()->query(), ['sort' => 'property_cost', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Property Cost
                                            </a>
                                        </th>

                                        <th class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('staff.dashboard', array_merge(request()->query(), ['sort' => 'application_status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Application Status
                                            </a>
                                        </th>

                                        <th class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('staff.dashboard', array_merge(request()->query(), ['sort' => 'payment_status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Payment Status
                                            </a>
                                        </th>

                                        <th class="px-4 py-3 whitespace-nowrap">Documents</th>
                                        
                                        <th class="px-4 py-3 whitespace-nowrap">Receipt</th>
                                        <th class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('staff.dashboard', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Submitted
                                            </a>
                                        </th>
                                        

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($applicants as $applicant)
                                        <tr
                                            onclick="window.location='{{ route('staff.applications.show', $applicant->id) }}'"
                                            class="border-b hover:bg-gray-50 transition text-sm align-top applicant-row cursor-pointer"
                                            data-status="{{ $applicant->application_status }}"
                                        >
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <input
                                                    type="checkbox"
                                                    name="selected_applicants[]"
                                                    value="{{ $applicant->id }}"
                                                    class="applicant-row-checkbox h-4 w-4 rounded border-gray-300"
                                                    aria-label="Select applicant {{ $applicant->reference_id }}"
                                                    onclick="event.stopPropagation()"
                                                >
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap font-semibold">
                                                {{ $applicant->reference_id }}
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $applicant->first_name }}
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $applicant->last_name }}
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $applicant->email }}
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $applicant->assignedStaffUser
                                                    ? $applicant->assignedStaffUser->first_name . ' ' . $applicant->assignedStaffUser->last_name
                                                    : 'Unassigned'
                                                }}
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $applicant->phone_number }}
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $applicant->address }}
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $applicant->city }}
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $applicant->state }}
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $applicant->company_name }}
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $applicant->occupation }}
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $applicant->years_employed }}
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $applicant->estate_name }}
                                            </td>

                                            <td class="px-4 py-3 font-semibold">
                                                <div class="flex min-w-36 items-center justify-between gap-3">
                                                    <span class="text-left">₦</span>
                                                    <span class="flex-1 text-right">{{ number_format($applicant->property_cost) }}</span>
                                                </div>
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                
                                                @php
                                                    $appStatusBadgeClass = match($applicant->application_status) {
                                                        'Payment Verified' => 'bg-green-100 text-green-800',
                                                        'Approved' => 'bg-green-100 text-green-800',
                                                        'Payment Receipt Submitted' => 'bg-yellow-100 text-yellow-800',
                                                        'Pending Payment' => 'bg-gray-100 text-gray-700',
                                                        'In Review' => 'bg-blue-100 text-blue-800',
                                                        'Rejected' => 'bg-red-100 text-red-800',
                                                        'Closed' => 'bg-gray-200 text-gray-800',
                                                        default => 'bg-gray-100 text-gray-700',
                                                    };
                                                @endphp
                                                
                                                @php
                                                    $currentRole = Auth::guard('staff')->user()?->role;
                                                    $canChangeStatus = in_array($currentRole, ['admin', 'manager'])
                                                        || (
                                                            $currentRole === 'reviewer'
                                                            && (int) $applicant->assigned_staff_user_id === (int) Auth::guard('staff')->id()
                                                        );
                                                @endphp

                                                @if($canChangeStatus)
                                                <form
                                                    method="POST"
                                                    action="{{ route('staff.applications.updateStatus', $applicant->id) }}"
                                                    onclick="event.stopPropagation()"
                                                    onsubmit="event.stopPropagation()"
                                                    >
                                                    @csrf
                                                    @method('PATCH')

                                                    <select
                                                        name="application_status"
                                                        onclick="event.stopPropagation()"
                                                        onchange="event.stopPropagation(); this.form.submit();"
                                                        class="border rounded-lg px-3 py-2 text-sm {{ $appStatusBadgeClass }}"
                                                    >
                                                        @if(in_array($applicant->application_status, ['Pending Payment', 'Pending Verification', 'Payment Receipt Submitted', 'Payment Verified']))
                                                            <option value="" selected disabled>{{ $applicant->application_status }}</option>
                                                        @endif

                                                        <option value="In Review" {{ $applicant->application_status == 'In Review' ? 'selected' : '' }}>In Review</option>
                                                        <option value="Rejected" {{ $applicant->application_status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                                        
                                                        @if(in_array(Auth::guard('staff')->user()?->role, ['admin', 'manager']))
                                                            <option value="Approved" {{ $applicant->application_status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                            <option value="Closed" {{ $applicant->application_status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                                        @endif

                                                    </select>
                                                </form>
                                                @else
                                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $appStatusBadgeClass }}">
                                                        {{ $applicant->application_status }}
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @php
                                                    $paymentBadgeClass = match($applicant->payment_status) {
                                                        'Paid' => 'bg-green-100 text-green-800',
                                                        'Pending Verification' => 'bg-yellow-100 text-yellow-800',
                                                        'Unpaid' => 'bg-gray-100 text-gray-700',
                                                        default => 'bg-red-100 text-red-800',
                                                    };
                                                @endphp

                                                @if(in_array(Auth::guard('staff')->user()?->role, ['admin', 'manager']))
                                                    <form
                                                        method="POST"
                                                        action="{{ route('staff.applications.updatePaymentStatus', $applicant->id) }}"
                                                        onclick="event.stopPropagation()"
                                                        onsubmit="event.stopPropagation()"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <select
                                                            name="payment_status"
                                                            onclick="event.stopPropagation()"
                                                            onchange="event.stopPropagation(); this.form.submit();"
                                                            class="border rounded-lg px-3 py-2 text-sm {{ $paymentBadgeClass }}"
                                                        >
                                                            <option value="Unpaid" {{ $applicant->payment_status == 'Unpaid' ? 'selected' : '' }}>Pending Payment</option>
                                                            <option value="Pending Verification" {{ $applicant->payment_status == 'Pending Verification' ? 'selected' : '' }}>Pending Verification</option>
                                                            <option value="Paid" {{ $applicant->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                                                        </select>
                                                    </form>
                                                @else
                                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $paymentBadgeClass }}">
                                                        {{ $applicant->payment_status === 'Unpaid' ? 'Pending Payment' : $applicant->payment_status }}
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">

                                                @if($applicant->documents->count())

                                                    <div class="space-y-1">

                                                        @foreach($applicant->documents as $document)

                                                            <div class="text-xs">

                                                                <a
                                                                    onclick="event.stopPropagation()"
                                                                    href="{{ route('staff.documents.view', $document->id) }}"
                                                                    target="_blank"
                                                                    class="text-blue-600 underline"
                                                                >
                                                                    {{ $document->original_name }}
                                                                </a>

                                                            </div>

                                                        @endforeach

                                                    </div>

                                                    @if($applicant->documents->count() > 1)

                                                        <a
                                                            href="{{ route('staff.applications.documents.zip', $applicant->id) }}"
                                                            onclick="event.stopPropagation()"
                                                            class="inline-block mt-2 text-xs bg-green-700 hover:bg-green-800 text-white px-3 py-2 rounded-lg"
                                                        >
                                                            Download ZIP
                                                        </a>

                                                    @endif

                                                @else

                                                    <span class="text-gray-400 text-xs">
                                                        No Docs
                                                    </span>

                                                @endif

                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">

                                                @if(in_array(Auth::guard('staff')->user()?->role, ['admin', 'manager']))
                                                    @if($applicant->receipt_path)
                                                        <a
                                                            href="{{ route('staff.receipts.view', $applicant->id) }}"
                                                            target="_blank"
                                                            class="text-blue-600 underline"
                                                            onclick="event.stopPropagation()"
                                                        >
                                                            View Receipt
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400">No Receipt</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400">Restricted</span>
                                                @endif

                                            </td>

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ $applicant->created_at->format('M d, Y g:i A') }}
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="20" class="px-4 py-8 text-center text-gray-500">

                                                No applications found.
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>

                    <div class="px-8 pb-8">

                        {{ $applicants->links() }}

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

            <script>
                const selectAllApplicants = document.getElementById('selectAllApplicants');
                const applicantRows = Array.from(document.querySelectorAll('.applicant-row'));

                function visibleApplicantRows() {
                    return applicantRows.filter((row) => !row.classList.contains('hidden'));
                }

                function updateSelectedApplicantsState() {
                    if (!selectAllApplicants) {
                        return;
                    }

                    const checkboxes = visibleApplicantRows()
                        .map((row) => row.querySelector('.applicant-row-checkbox'))
                        .filter(Boolean);
                    const checkedCount = checkboxes.filter((checkbox) => checkbox.checked).length;

                    selectAllApplicants.checked = checkboxes.length > 0 && checkedCount === checkboxes.length;
                    selectAllApplicants.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
                }

                selectAllApplicants?.addEventListener('change', function () {
                    visibleApplicantRows().forEach((row) => {
                        const checkbox = row.querySelector('.applicant-row-checkbox');

                        if (checkbox) {
                            checkbox.checked = selectAllApplicants.checked;
                        }
                    });

                    updateSelectedApplicantsState();
                });

                document.querySelectorAll('.applicant-row-checkbox').forEach((checkbox) => {
                    checkbox.addEventListener('change', updateSelectedApplicantsState);
                });

                updateSelectedApplicantsState();
            </script>

@endsection
