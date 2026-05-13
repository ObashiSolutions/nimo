{{-- This extends the main layout file, which includes security and the login/logout links --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Applicant Dashboard</h1>
        
        {{-- This is the button that links to the Export function in your ExportController --}}
        <a href="{{ route('admin.export') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">
            Download Applicants (Excel)
        </a>
    </div>

    {{-- Display any error messages (e.g., if the export package isn't working) --}}
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
            <strong class="font-bold">Error:</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Applicants Table -->
    <div class="bg-white shadow-2xl rounded-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Submitted</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Income</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property Cost</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Files</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- 
                      This @forelse loop checks if the $applicants variable (from the Controller) is empty.
                      If it's not empty, it loops through each one and creates a table row.
                    --}}
                    @forelse ($applicants as $applicant)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{-- Format the submission date nicely --}}
                                {{ $applicant->created_at->format('M d, Y - g:ia') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $applicant->first_name }} {{ $applicant->last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div class="font-medium">{{ $applicant->email }}</div>
                                <div>{{ $applicant->phone_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-bold">
                                {{ $applicant->currency_code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{-- Format the number with commas --}}
                                {{ number_format($applicant->income) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ number_format($applicant->property_cost) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                                {{-- Show if files were uploaded --}}
                                @if($applicant->pdf_path)
                                    <div>PDF (Yes)</div>
                                @endif
                                @if($applicant->image_path)
                                    <div>Image (Yes)</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        {{-- This shows if the database table is empty (no applicants yet) --}}
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 text-lg">
                                No applications have been submitted yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <!-- We need to load Tailwind for the dashboard, as the 'layouts.app' file uses Bootstrap -->
    <script src="https://cdn.tailwindcss.com"></script>
@endpush