<h2>Payment Verified Successfully</h2>

<p>Dear {{ $applicant->first_name }},</p>

<p>
    Your manual transfer receipt has been reviewed and your payment has been verified successfully.
</p>

<hr>

<p><strong>Reference ID:</strong> {{ $applicant->reference_id }}</p>
<p><strong>Payment Reference:</strong> {{ $payment->reference }}</p>
<p><strong>Amount:</strong> ₦{{ number_format($payment->amount / 100) }}</p>
<p><strong>Status:</strong> Verified</p>

<hr>

<p>
    Your mortgage pre-approval application will now proceed to the next review stage.
</p>

<p>
    Thank you,<br>
    Nigeria Mortgages
</p>