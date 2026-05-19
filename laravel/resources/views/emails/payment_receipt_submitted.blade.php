<h2>Mortgage Payment Receipt Submitted</h2>

<p>
    A payment receipt has been submitted.
</p>

<hr>

<p>
    <strong>Reference ID:</strong>
    {{ $applicant->reference_id }}
</p>

<p>
    <strong>Name:</strong>
    {{ $applicant->first_name }}
    {{ $applicant->last_name }}
</p>

<p>
    <strong>Email:</strong>
    {{ $applicant->email }}
</p>

<p>
    <strong>Phone:</strong>
    {{ $applicant->phone_number }}
</p>

<p>
    <strong>Property Address:</strong>
    {{ $applicant->property_address }}
</p>

<p>
    <strong>Property Cost:</strong>
    ₦{{ number_format($applicant->property_cost) }}
</p>

<hr>

<p>
    The uploaded payment receipt is attached to this email.
</p>