<h2>Copy of Your Mortgage Pre-Approval Application</h2>

<p>Dear {{ $data['first_name'] ?? 'Applicant' }},</p>

<p>
    Thank you for submitting your mortgage pre-approval application.
    A copy of your submitted application details is below for your records.
</p>

<hr>

<p><strong>Name:</strong> {{ $data['first_name'] ?? '' }} {{ $data['last_name'] ?? '' }}</p>
<p><strong>Email:</strong> {{ $data['email'] ?? '' }}</p>
<p><strong>Phone:</strong> {{ $data['phone_number'] ?? '' }}</p>
<p><strong>Address:</strong> {{ $data['address'] ?? '' }}</p>
<p><strong>City:</strong> {{ $data['city'] ?? '' }}</p>
<p><strong>State:</strong> {{ $data['state'] ?? '' }}</p>

<hr>

<p><strong>Company:</strong> {{ $data['company_name'] ?? '' }}</p>
<p><strong>Occupation:</strong> {{ $data['occupation'] ?? '' }}</p>
<p><strong>Years Employed:</strong> {{ $data['years_employed'] ?? '' }}</p>
<p><strong>Title:</strong> {{ $data['title'] ?? '' }}</p>

<hr>

<p><strong>Agent:</strong> {{ $data['agent_name'] ?? '' }}</p>
<p><strong>Estate:</strong> {{ $data['estate_name'] ?? '' }}</p>
<p><strong>Property Address:</strong> {{ $data['property_address'] ?? '' }}</p>
<p><strong>Property Cost:</strong> ₦{{ isset($data['property_cost']) ? number_format($data['property_cost']) : '' }}</p>

<hr>

<p>
    Your uploaded documents, if any, are attached to this email.
</p>

<p>
    Nigeria Mortgages will review your application and contact you if additional information is needed.
</p>

<p>
    Thank you,<br>
    Nigeria Mortgages
</p>