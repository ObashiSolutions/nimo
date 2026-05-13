<h2>New Mortgage Pre-Approval Application</h2>

<p><strong>Name:</strong> {{ $data['first_name'] }} {{ $data['last_name'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>
<p><strong>Phone:</strong> {{ $data['phone_number'] }}</p>
<p><strong>Address:</strong> {{ $data['address'] }}</p>
<p><strong>Agent Name (if any):</strong> {{ $data['agent_name'] ?? 'N/A' }}</p>
<p><strong>Property Cost:</strong>
    @if(!empty($data['property_cost']))
        {{ number_format($data['property_cost']) }}
    @else
        Not specified
    @endif
</p>
<hr>

<p>This applicant submitted the form on NigeriaMortgages.com.</p>
