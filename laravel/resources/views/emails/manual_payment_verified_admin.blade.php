<h2>Manual Payment Verified</h2>

<p>
    A manual payment receipt has been verified by staff.
</p>

<hr>

<p><strong>Reference ID:</strong> {{ $applicant->reference_id }}</p>
<p><strong>Name:</strong> {{ $applicant->first_name }} {{ $applicant->last_name }}</p>
<p><strong>Email:</strong> {{ $applicant->email }}</p>
<p><strong>Phone:</strong> {{ $applicant->phone_number }}</p>

<hr>

<p><strong>Payment Reference:</strong> {{ $payment->reference }}</p>
<p><strong>Amount:</strong> ₦{{ number_format($payment->amount / 100) }}</p>
<p><strong>Status:</strong> Verified</p>