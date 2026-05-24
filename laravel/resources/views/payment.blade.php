<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mortgage Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <!--Header-->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-center">
            <img
                src="{{ asset('images/nigeria-mortgage-logo-cthru.png') }}"
                class="h-12 w-auto"
                alt="Nigeria Mortgages"
            >
        </div>
    </header>
    
    <!--Hero-->
    <section class="bg-green-800 text-white">
        <div class="max-w-5xl mx-auto px-6 py-10 text-center">
            <h1 class="text-3xl md:text-5xl font-extrabold uppercase tracking-wide">
                Mortgage Pre-Approval Payment
            </h1>
            <p class="mt-3 text-green-100">
                Complete your payment to continue your pre-approval processing.
            </p>
        </div>
    </section>

    <!--Main Content-->
    <div class="max-w-2xl mx-auto py-10 px-4">

        <div class="bg-white rounded-xl shadow-md p-6 space-y-6">

            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900">
                    Mortgage Pre-Approval Payment
                </h1>

                <p class="text-sm text-gray-600 mt-2">
                    Reference ID:
                    <span class="font-semibold">
                        {{ $applicant->reference_id }}
                    </span>
                </p>
            </div>

            <div class="rounded-xl border-l-4 border-yellow-500 bg-yellow-50 p-5 text-yellow-900 space-y-3">
                <p class="font-semibold">
                    Your application has been submitted successfully. However, your mortgage pre-approval application will not be reviewed or processed until payment has been received and verified.
                </p>

                <p class="text-sm leading-relaxed">
                    If you leave this page before uploading your payment receipt, you may need to submit a new application and re-upload all required documents.
                </p>
            </div>

            <div class="border rounded-lg p-4 bg-gray-50">
                <h2 class="font-semibold text-lg mb-3">
                    Payment Instructions
                </h2>

                <div class="space-y-2 text-sm text-gray-700">

                    <p>
                        Amount:
                        <span class="font-bold text-black">
                            ₦200,000
                        </span>
                    </p>

                    <p>
                        Bank Name:
                        <span class="font-semibold">
                            Zenith Bank
                        </span>
                    </p>

                    <p>
                        Account Name:
                        <span class="font-semibold">
                            Nigeria Mortgages
                        </span>
                    </p>

                    <p>
                        Account Number:
                        <span class="font-semibold">
                            10144-18111
                        </span>
                    </p>

                </div>
            </div>


            <!-- Instructions for applicants -->
            <div class="text-sm text-gray-600 leading-relaxed">
                You may transfer directly or use the <strong> Online Payment Options</strong>. If you have already made a transfer, please upload your payment receipt below. Once acknowledged, your application will be matched with a mortgage specialist from an MREIF-affiliated
                banking partner.
            </div>

            <!-- Online payment options -->
            <div class="mt-6 border rounded-xl p-5 bg-gray-50">

                <h3 class="font-bold text-lg mb-4">
                    Online Payment Options
                </h3>

                <div class="space-y-4">

                    <form
                        method="POST"
                        action="{{ route('flutterwave.initialize', $applicant->id) }}"
                    >
                        @csrf

                        <button
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white py-4 rounded-xl font-bold"
                        >
                            Pay Online with Flutterwave
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('paystack.initialize', $applicant->id) }}"
                    >
                        @csrf

                        <button
                            class="w-full bg-green-700 hover:bg-green-800 text-white py-4 rounded-xl font-bold"
                        >
                            Pay Online with Paystack
                        </button>
                    </form>

                </div>

            </div>



            @if($applicant->payment_status === 'Paid')

                <div class="bg-green-100 border border-green-300 text-green-800 rounded-xl p-6 text-center">

                    <h2 class="text-2xl font-bold mb-2">
                        Payment Verified
                    </h2>

                    <p>
                        Your payment has already been received and verified.
                    </p>

                </div>

            @else
                <!-- Paystack payment form (placeholder for future integration) -->
                <form
                    method="POST"
                    action="{{ route('paystack.initialize', $applicant->id) }}"
                    class="mb-6"
                >
                    @csrf

                    <button
                        class="w-full bg-green-700 hover:bg-green-800 text-white py-4 rounded-xl font-bold text-lg"
                    >
                        Pay Online with Paystack
                    </button>
                </form>

                <div class="text-center text-gray-500 my-6">
                    OR
                </div>
                
                <!-- Payment receipt upload form -->
                <form
                    action="{{ route('application.payment.submit', $applicant->id) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-5"
                >
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Upload Manual Transfer Receipt
                        </label>

                        <p class="text-sm text-gray-600 mb-4">
                            Use this only if you paid by direct bank transfer or had trouble completing online payment.
                        </p>
                        
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
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 rounded-lg"
                    >
                        Submit Payment Receipt
                    </button>

                </form>
            @endif
        </div>

    </div>

    <footer class="bg-white py-10 mt-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between gap-6 text-sm text-gray-600">
            <div>
                <img src="{{ asset('images/nigeria-mortgage-logo-cthru.png') }}" class="h-12 mb-3" alt="Nigeria Mortgages">
                <p>Secure mortgage pre-approval for Nigerian property buyers.</p>
            </div>

            <div>
                <p class="font-bold text-gray-900">In association with</p>
                <p>Power Players · MOFI · MREIF · ARM</p>
            </div>

            <div>
                <p>© {{ date('Y') }} Nigeria Mortgages</p>
            </div>
        </div>
    </footer>
</body>
</html>
