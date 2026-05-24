<h2>Paystack Payment Verified</h2>

<p>
    A Paystack payment has been verified successfully.
</p>

<hr>

<p><strong>Reference ID:</strong> {{ $applicant->reference_id }}</p>
<p><strong>Name:</strong> {{ $applicant->first_name }} {{ $applicant->last_name }}</p>
<p><strong>Email:</strong> {{ $applicant->email }}</p>
<p><strong>Phone:</strong> {{ $applicant->phone_number }}</p>

<hr>

<p><strong>Payment Reference:</strong> {{ $payment->reference }}</p>
<p><strong>Amount Paid:</strong> ₦{{ number_format($payment->amount / 100) }}</p>
<p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>

<hr>

<p>
    The applicant has been marked as paid and payment verified.
</p>