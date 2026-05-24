<h2>Payment Received Successfully</h2>

<p>Dear {{ $applicant->first_name }},</p>

<p>
    Your payment for your Nigeria Mortgages pre-approval application has been received successfully.
</p>

<hr>

<p><strong>Reference ID:</strong> {{ $applicant->reference_id }}</p>
<p><strong>Payment Reference:</strong> {{ $payment->reference }}</p>
<p><strong>Amount Paid:</strong> ₦{{ number_format($payment->amount / 100) }}</p>
<p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>

<hr>

<p>
    Your application will now proceed to the next review stage.
</p>

<p>
    Thank you,<br>
    Nigeria Mortgages
</p>