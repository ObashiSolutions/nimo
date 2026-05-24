<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mortgage Pre-Approval – Application Submitted</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f7fafc; }
        .hero-background {
            background-color: #156b2e; /* deep navy, same family as welcome */
        }
    </style>
</head>




<body class="antialiased">
    <!-- TOP NAV WITH LOGO --> <!--Header-->
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
                Thank You & Congratulations!
            </h1>
            <p class="mt-3 text-green-100 text-lg">
                Your pre-approval application has been received.
            </p>
        </div>
    </section>
    
    <div class="min-h-screen flex flex-col">
        <!-- Top hero -->
        
    
        <!-- Main content -->
        <main class="flex-1 py-8 px-4">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white shadow-lg rounded-xl border border-gray-100 px-6 sm:px-10 py-8 sm:py-10">
                    <!-- Green check -->
                    <div class="flex justify-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="h-7 w-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
    
                    <!-- Headline + dynamic success message -->
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 text-center mb-2">
                        Application Submitted Successfully
                    </h2>
    
                    @if(session('success_message'))
                        <p class="text-sm sm:text-base text-gray-700 text-center mb-4">
                            {{ session('success_message') }}
                        </p>
                    @else
                        <p class="text-sm sm:text-base text-gray-700 text-center mb-4">
                        </p>
                    @endif
    

                    @if(session('success_type') === 'online_payment')
                    <p class="text-xs sm:text-sm text-gray-600 text-center mb-6">
                        Our team is reviewing your details. A confirmation may have been sent to the email address you provided. Check your spam. Please also keep your
                        WhatsApp available so we can contact you quickly if any additional information is needed.
                    </p>
                    @elseif(session('success_type') === 'manual_receipt')
                    <p class="text-xs sm:text-sm text-gray-600 text-center mb-6">
                        Your payment receipt has been submitted successfully and is pending verification. We shall contact you via email along with a WhatsApp notification within 24 hours. Please keep your WhatsApp open.
                    </p>
                    @else
                    <p class="text-xs sm:text-sm text-gray-600 text-center mb-6">
                        Your application has been submitted successfully. However, your mortgage pre-approval application will not be reviewed or processed until payment has been received and verified.
                    </p>                    
                    @endif

                    <!-- Next steps -->
                    <div class="mt-4 border-t border-gray-200 pt-4">
                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-2">
                            What happens next?
                        </h3>
                        <ul class="text-xs sm:text-sm text-gray-700 space-y-1 list-disc list-inside mb-4">
                            <li>A mortgage adviser will review your information and supporting documents.</li>
                            <li>You may receive a call or WhatsApp message to clarify any details.</li>
                            <li>If your profile fits, you’ll be guided through full underwriting and next steps toward offer issuance.</li>
                            <li>Keep copies of your pay slips, bank statements, and ID accessible in case they are requested.</li>
                        </ul>
                    </div>
    
                    <!-- WhatsApp + back home buttons -->
                    <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a
                            href="https://wa.me/2349011240456"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold w-full sm:w-auto text-center"
                        >
                            Chat with us on WhatsApp
                        </a>
                        <a
                            href="{{ url('/') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-semibold w-full sm:w-auto text-center"
                        >
                            Back to Application Page
                        </a>
                    </div>
    
                    <!-- Fine print -->
                    <div class="mt-6 border-t border-gray-200 pt-3">
                        <p class="text-[11px] sm:text-xs text-gray-500 leading-relaxed">
                            Submission of this form and receipt of this confirmation do not constitute a binding mortgage offer.
                            All facilities remain subject to full underwriting, document verification, credit assessment, and the
                            terms and conditions of our mortgage partners.
                        </p>
                    </div>
                </div>
    
                <!-- Logos footer -->
                <div class="mt-8 border-t border-gray-200 pt-4">
                    <p class="text-xs text-gray-500 mb-2">
                        In association with:
                    </p>
                    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-700">
                        <span class="font-semibold">Power Players</span>
                        <span class="w-px h-4 bg-gray-300"></span>
                        <span class="font-semibold">MOFI</span>
                        <span class="w-px h-4 bg-gray-300"></span>
                        <span class="font-semibold">MRIEF</span>
                        <span class="w-px h-4 bg-gray-300"></span>
                        <span class="font-semibold">ARM</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
    </body>
</html>
