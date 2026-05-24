@extends('layouts.staff')

@section('content')

<div class="px-8 py-8">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Total Payments</p>
            <h3 class="text-3xl font-bold mt-2">
                {{ \App\Models\Payment::count() }}
            </h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Successful Payments</p>
            <h3 class="text-3xl font-bold mt-2 text-green-700">
                {{ \App\Models\Payment::where('status', 'success')->count() }}
            </h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Pending Manual Verification</p>
            <h3 class="text-3xl font-bold mt-2 text-yellow-700">
                {{ \App\Models\Payment::where('provider', 'manual_transfer')->where('status', 'pending_verification')->count() }}
            </h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Failed / Other</p>
            <h3 class="text-3xl font-bold mt-2 text-red-700">
                {{ \App\Models\Payment::whereNotIn('status', ['success', 'pending_verification'])->count() }}
            </h3>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow p-6 mb-6">

        <h2 class="text-xl font-bold mb-5">
            Payment Reports
        </h2>

        <form method="GET" class="flex flex-wrap gap-3">

            <select name="provider" class="border rounded-lg px-4 py-3">
                <option value="">All Providers</option>
                <option value="paystack" {{ request('provider') === 'paystack' ? 'selected' : '' }}>
                    Paystack
                </option>
                <option value="manual_transfer" {{ request('provider') === 'manual_transfer' ? 'selected' : '' }}>
                    Manual Transfer
                </option>
            </select>

            <select name="status" class="border rounded-lg px-4 py-3">
                <option value="">All Statuses</option>
                <option value="initialized" {{ request('status') === 'initialized' ? 'selected' : '' }}>
                    Initialized
                </option>
                <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>
                    Success
                </option>
                <option value="pending_verification" {{ request('status') === 'pending_verification' ? 'selected' : '' }}>
                    Pending Verification
                </option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>
                    Failed
                </option>
            </select>

            <button class="bg-green-800 hover:bg-green-900 text-white px-5 py-3 rounded-lg font-semibold">
                Filter
            </button>

            <a href="{{ route('staff.payments.index') }}" class="bg-gray-700 hover:bg-black text-white px-5 py-3 rounded-lg font-semibold">
                Reset
            </a>

        </form>

    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1100px]">

                <thead class="bg-gray-100">
                    <tr class="text-left text-sm text-gray-700">
                        <th class="px-4 py-3">Applicant</th>
                        <th class="px-4 py-3">Reference ID</th>
                        <th class="px-4 py-3">Provider</th>
                        <th class="px-4 py-3">Payment Ref</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Date</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($payments as $payment)

                        <tr class="border-b text-sm hover:bg-gray-50">

                            <td class="px-4 py-3">
                                @if($payment->applicant)
                                    <a
                                        href="{{ route('staff.applications.show', $payment->applicant->id) }}"
                                        class="text-blue-600 underline"
                                    >
                                        {{ $payment->applicant->first_name }}
                                        {{ $payment->applicant->last_name }}
                                    </a>
                                @else
                                    Deleted Applicant
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                {{ $payment->applicant->reference_id ?? 'N/A' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ ucfirst(str_replace('_', ' ', $payment->provider)) }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $payment->reference }}
                            </td>

                            <td class="px-4 py-3">
                                ₦{{ number_format($payment->amount / 100) }}
                            </td>

                            <td class="px-4 py-3">
                                {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $payment->created_at->format('M d, Y g:i A') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                No payments found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="mt-6">
        {{ $payments->links() }}
    </div>

</div>

@endsection