@extends('layouts.staff')

@section('content')



<div class="px-4 md:px-8 py-6 md:py-8">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>

            <h1 class="text-3xl font-bold">
                {{ $applicant->first_name }}
                {{ $applicant->last_name }}
            </h1>

            <p class="text-gray-500 mt-1">
                {{ $applicant->reference_id }}
            </p>


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
                $paymentBadgeClass = match($applicant->payment_status) {
                    'Paid' => 'bg-green-100 text-green-800',
                    'Pending Verification' => 'bg-yellow-100 text-yellow-800',
                    'Unpaid' => 'bg-gray-100 text-gray-700',
                    default => 'bg-red-100 text-red-800',
                };
            @endphp

            <div class="mt-3 flex flex-wrap gap-2">

                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $appStatusBadgeClass }}">
                    Application: {{ $applicant->application_status }}
                </span>

                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $paymentBadgeClass }}">
                    Payment: {{ $applicant->payment_status }}
                </span>

            </div>

        </div>

        <a
            href="{{ route('staff.dashboard') }}"
            class="inline-block bg-gray-800 hover:bg-black text-white px-5 py-3 rounded-lg text-center"
        >
            Back To Dashboard
        </a>

        <!-- View Name of Assigned Staff Section -->
        <div class="text-sm text-gray-600 font-semibold">
            Assigned to:
            {{ $applicant->assignedStaffUser
                ? $applicant->assignedStaffUser->first_name . ' ' . $applicant->assignedStaffUser->last_name
                : 'Unassigned'
            }}
        </div>

        <!-- Admin/Manager Assignment Form -->
        @if(in_array(Auth::guard('staff')->user()?->role, ['admin', 'manager']))

            <div class="mt-4">

                <form
                    method="POST"
                    action="{{ route('staff.applicants.assign', $applicant->id) }}"
                    class="flex flex-wrap gap-3 items-center"
                >
                    @csrf
                    @method('PATCH')

                    <select
                        name="assigned_staff_user_id"
                        class="border rounded-lg px-4 py-3"
                    >

                        <option value="">
                            Unassigned
                        </option>

                        @foreach($assignableStaffUsers as $staffUser)

                            <option
                                value="{{ $staffUser->id }}"
                                @selected((int) $applicant->assigned_staff_user_id === (int) $staffUser->id)
                            >
                                {{ $staffUser->first_name }}
                                {{ $staffUser->last_name }}
                                ({{ ucfirst($staffUser->role) }})
                            </option>

                        @endforeach

                    </select>

                    <button
                        class="bg-green-700 hover:bg-green-800 text-white px-5 py-3 rounded-lg font-semibold"
                    >
                        Update Assignment
                    </button>

                </form>

                @if($applicant->assignedStaffUser)

                    <div class="text-sm text-gray-500 mt-3">

                        Assigned to:
                        {{ $applicant->assignedStaffUser->first_name }}
                        {{ $applicant->assignedStaffUser->last_name }}

                        @if($applicant->assigned_at)
                            · {{ $applicant->assigned_at->format('M d, Y g:i A') }}
                        @endif

                    </div>

                @endif

            </div>

        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-xl shadow p-6 space-y-4">

            <h2 class="text-xl font-bold border-b pb-3">
                Personal Information
            </h2>

            <div>
                <strong>Email:</strong>
                {{ $applicant->email }}
            </div>

            <div>
                <strong>Phone:</strong>
                {{ $applicant->phone_number }}
            </div>

            <div>
                <strong>Address:</strong>
                {{ $applicant->address }}
            </div>

            <div>
                <strong>City:</strong>
                {{ $applicant->city }}
            </div>

            <div>
                <strong>State:</strong>
                {{ $applicant->state }}
            </div>

        </div>

        <div class="bg-white rounded-xl shadow p-6 space-y-4">

            <h2 class="text-xl font-bold border-b pb-3">
                Employment Information
            </h2>

            <div>
                <strong>Company:</strong>
                {{ $applicant->company_name }}
            </div>

            <div>
                <strong>Occupation:</strong>
                {{ $applicant->occupation }}
            </div>

            <div>
                <strong>Years Employed:</strong>
                {{ $applicant->years_employed }}
            </div>

            <div>
                <strong>Title:</strong>
                {{ $applicant->title }}
            </div>

        </div>

        <div class="bg-white rounded-xl shadow p-6 space-y-4">

            <h2 class="text-xl font-bold border-b pb-3">
                Property Information
            </h2>

            <div>
                <strong>Estate:</strong>
                {{ $applicant->estate_name }}
            </div>

            <div>
                <strong>Agent:</strong>
                {{ $applicant->agent_name }}
            </div>

            <div>
                <strong>Property Address:</strong>
                {{ $applicant->property_address }}
            </div>

            <div>
                <strong>Property Cost:</strong>
                <span class="inline-flex min-w-36 items-center justify-between gap-3 font-semibold">
                    <span class="text-left">₦</span>
                    <span class="flex-1 text-right">{{ number_format($applicant->property_cost) }}</span>
                </span>
            </div>

        </div>

        <div class="bg-white rounded-xl shadow p-6 space-y-4">

            <h2 class="text-xl font-bold border-b pb-3">
                Documents
            </h2>

            @forelse($applicant->documents as $document)

                <div>

                    <a
                        href="{{ route('staff.documents.view', $document->id) }}"
                        target="_blank"
                        class="text-blue-600 underline"
                    >
                        {{ $document->original_name }}
                    </a>

                </div>

            @empty

                <div class="text-gray-400">
                    No documents uploaded.
                </div>

            @endforelse

            @if($applicant->documents->count() > 1)

                <div class="mt-4">

                    <a
                        href="{{ route('staff.applications.documents.zip', $applicant->id) }}"
                        class="inline-block bg-green-700 hover:bg-green-800 text-white px-5 py-3 rounded-lg font-semibold"
                    >
                        Download All Documents ZIP
                    </a>

                </div>

            @endif

            <hr>

            <h3 class="font-bold">
                Payment Receipt
            </h3>

            @if($applicant->receipt_path && in_array(Auth::guard('staff')->user()?->role, ['admin', 'manager']))

                <a
                    href="{{ route('staff.receipts.view', $applicant->id) }}"
                    target="_blank"
                    class="text-blue-600 underline"
                >
                    View Receipt
                </a>

            @elseif($applicant->receipt_path)

                <div class="text-gray-400">
                    Restricted
                </div>

            @else

                @if(in_array(Auth::guard('staff')->user()?->role, ['admin', 'manager']))
                    <form
                        method="POST"
                        action="{{ route('staff.applications.receipts.store', $applicant->id) }}"
                        enctype="multipart/form-data"
                        class="space-y-3 rounded-lg border border-yellow-200 bg-yellow-50 p-4"
                    >
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-yellow-900 mb-2">
                                Upload Payment Receipt
                            </label>

                            <input
                                type="file"
                                name="payment_receipt"
                                accept=".jpg,.jpeg,.png,.pdf"
                                required
                                class="block w-full text-sm"
                            >
                        </div>

                        <button
                            type="submit"
                            class="bg-green-700 hover:bg-green-800 text-white px-5 py-3 rounded-lg font-semibold"
                        >
                            Upload Receipt
                        </button>
                    </form>
                @else
                    <div class="text-gray-400">
                        No receipt uploaded.
                    </div>
                @endif

            @endif

        </div>

    </div>
    

    <div class="mt-8 bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold border-b pb-3 mb-4">
            Tasks / Follow-Ups
        </h2>

        <form
            method="POST"
            action="{{ route('staff.applications.tasks.store', $applicant->id) }}"
            class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6"
        >
            @csrf

            <input
                type="text"
                name="task"
                class="border rounded-lg px-4 py-3 md:col-span-2"
                placeholder="Example: Call borrower for missing bank statement"
                required
            >

            <input
                type="date"
                name="due_date"
                class="border rounded-lg px-4 py-3"
            >

            <button
                class="justify-self-start bg-green-700 hover:bg-green-800 text-white px-5 py-3 rounded-lg font-semibold"
            >
                Add Task
            </button>
        </form>

        <div class="space-y-4">
            @forelse($applicant->tasks->sortByDesc('created_at') as $task)
                <div class="
                        border
                        rounded-lg
                        p-4
                        flex
                        items-center
                        justify-between
                        gap-4
                        flex flex-col md:flex-row md:items-center md:justify-between

                        @if(
                            $task->status !== 'Completed'
                            &&
                            $task->due_date
                            &&
                            \Carbon\Carbon::parse($task->due_date)->isPast()
                        )
                            bg-red-50 border-red-300
                        @else
                            bg-gray-50
                        @endif
                    ">
                    <div>
                        <div class="font-semibold">
                            {{ $task->task }}
                        </div>

                        <div class="text-sm text-gray-500 mt-1">
                            Due:
                            {{ $task->due_date ?? 'No due date' }}

                            @if(
                                $task->status !== 'Completed'
                                &&
                                $task->due_date
                                &&
                                \Carbon\Carbon::parse($task->due_date)->isPast()
                            )

                                <span class="ml-2 text-red-600 font-semibold">
                                    OVERDUE
                                </span>

                            @endif
                            · Status:
                            {{ $task->status }}
                        </div>
                    </div>

                    @if($task->status !== 'Completed')
                        <form
                            method="POST"
                            action="{{ route('staff.tasks.complete', $task->id) }}"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold"
                            >
                                Mark Complete
                            </button>
                        </form>
                    @endif
                </div>

            @empty

                <p class="text-gray-400">
                    No tasks yet.
                </p>
            @endforelse
        </div>
    </div>



    
    <!-- Internal Notes Section -->
    <div class="mt-8 bg-white rounded-xl shadow p-6">

        <h2 class="text-xl font-bold border-b pb-3 mb-4">
            Internal Notes
        </h2>

        @if(session('success_message'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4">
                {{ session('success_message') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('staff.applications.notes.store', $applicant->id) }}"
            class="mb-6"
        >
            @csrf

            <textarea
                name="note"
                rows="4"
                class="w-full border rounded-lg px-4 py-3"
                placeholder="Add internal note..."
                required
            ></textarea>

            <button
                class="mt-3 bg-green-700 hover:bg-green-800 text-white px-5 py-3 rounded-lg font-semibold"
            >
                Add Note
            </button>
        </form>

        <div class="space-y-4">

            @forelse($applicant->notes->sortByDesc('created_at') as $note)

                <div class="border rounded-lg p-4 bg-gray-50">

                    <div class="text-sm text-gray-500 mb-2">
                        {{ $note->created_by }} · {{ $note->created_at->format('M d, Y g:i A') }}
                    </div>

                    <div class="text-gray-800">
                        {{ $note->note }}
                    </div>

                </div>

            @empty

                <p class="text-gray-400">
                    No notes yet.
                </p>

            @endforelse

        </div>

    </div>
</div>


<div class="bg-white rounded-2xl shadow p-6 mt-8">

    <h2 class="text-xl font-bold mb-6">
        Payment History
    </h2>

    <div class="overflow-x-auto">

        <table class="w-full min-w-[800px]">

            <thead class="bg-gray-100">
                <tr class="text-left text-sm text-gray-700">
                    <th class="px-4 py-3">Provider</th>
                    <th class="px-4 py-3">Reference</th>
                    <th class="px-4 py-3">Amount</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>

            <tbody>

                @forelse($applicant->payments as $payment)

                    <tr class="border-b text-sm">
                        <td class="px-4 py-3">
                            {{ ucfirst($payment->provider) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $payment->reference }}
                        </td>

                        <td class="px-4 py-3">
                            ₦{{ number_format($payment->amount / 100) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ ucfirst(str_replace('_', ' ', $payment->status)) }}

                            @if(
                                $payment->provider === 'manual_transfer'
                                &&
                                $payment->status !== 'success'
                                &&
                                in_array(Auth::guard('staff')->user()?->role, ['admin', 'manager'])
                            )

                                <form
                                    method="POST"
                                    action="{{ route('staff.payments.verifyManual', $payment->id) }}"
                                    class="mt-3"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-xs font-semibold"
                                    >
                                        Verify Manual Payment
                                    </button>
                                </form>

                            @endif
                        </td>

                        <td class="px-4 py-3">
                            {{ $payment->created_at->format('M d, Y g:i A') }}
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            No payment attempts yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<div class="bg-white rounded-2xl shadow p-6 mt-8">

    <h2 class="text-xl font-bold mb-6">
        Status History
    </h2>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[700px]">
            <thead class="bg-gray-100">
                <tr class="text-left text-sm text-gray-700">
                    <th class="px-4 py-3">Old Status</th>
                    <th class="px-4 py-3">New Status</th>
                    <th class="px-4 py-3">Changed By</th>
                    <th class="px-4 py-3">Date</th>
                </tr>

            </thead>
            <tbody>

                @forelse($applicant->statusHistories as $history)

                    <tr class="border-b text-sm">
                        <td class="px-4 py-3">
                            {{ $history->old_status ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $history->new_status }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $history->changed_by ?? 'System' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $history->created_at->format('M d, Y g:i A') }}
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                            No status changes yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>
    </div>
</div>


<div class="bg-white rounded-2xl shadow p-6 mt-8">

    <h2 class="text-xl font-bold mb-6">
        Applicant Timeline
    </h2>

    <div class="space-y-4">

        @forelse($applicant->timeline as $event)

            <div class="border-l-4 border-green-700 pl-4 py-2">

                <div class="flex items-center justify-between gap-4 flex-wrap">

                    <div>

                        <p class="font-semibold text-gray-900">
                            {{ $event->message }}
                        </p>

                        <p class="text-sm text-gray-500 mt-1">

                            {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}

                            @if($event->performed_by)
                                · {{ $event->performed_by }}
                            @endif

                        </p>

                    </div>

                    <div class="text-sm text-gray-400 whitespace-nowrap">
                        {{ $event->created_at->format('M d, Y g:i A') }}
                    </div>

                </div>

            </div>

        @empty

            <p class="text-gray-500">
                No timeline activity yet.
            </p>

        @endforelse

    </div>

</div>

@endsection
