<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nigeria Mortgages</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        details summary::-webkit-details-marker {
            display: none;
        }

        details[open] .plus {
            transform: rotate(45deg);
        }
    </style>
</head>

<body class="bg-[#f7f8f5] text-gray-900">

<header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="#top">
            <img src="{{ asset('images/nigeria-mortgage-logo-cthru.png') }}" class="h-14 w-auto" alt="Nigeria Mortgages">
        </a>

        <nav class="hidden md:flex items-center gap-8 text-base font-bold text-gray-700">
            <a href="#apply" class="hover:text-green-800">Who Can Apply</a>
            <a href="#documents" class="hover:text-green-800">Documents</a>
            <a href="#faq" class="hover:text-green-800">FAQ</a>
        </nav>

        <a href="{{ route('application.form') }}" class="bg-green-800 hover:bg-green-900 text-white px-6 py-3 rounded-full font-bold text-base">
            Get Pre-Approved
        </a>
    </div>
</header>

<section id="top" class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">

        <div>
            <p class="uppercase tracking-[0.25em] text-3xl text-green-800 font-extrabold mb-4">
                Self-Certification
            </p>

            <h1 class="text-5xl md:text-6xl font-extrabold leading-tight">
                Qualify to secure up to ₦100M! 
            </h1>

            <p class="mt-8 text-sm md:text-base uppercase tracking-[0.2em] text-green-800 font-extrabold">
                Answer these questions before starting your secure pre-approval application.
            </p>

            <ol class="mt-8 space-y-6 text-xl text-gray-800 font-semibold">
                <li class="flex items-start gap-4">
                    <span class="text-green-800 font-extrabold text-2xl">1.</span>
                    <span>Does the property have its title document (ex: C. of O.)?</span>
                </li>

                <li class="flex items-start gap-4">
                    <span class="text-green-800 font-extrabold text-2xl">2.</span>
                    <span>Is the property developed to at least carcass level?</span>
                </li>

                <li class="flex items-start gap-4">
                    <span class="text-green-800 font-extrabold text-2xl">3.</span>
                    <span>Is your monthly income from all verifiable accounts between ₦800K to ₦3M?</span>
                </li>
            </ol>
            <br>
            <p class="mt-6 text-lg font-bold text-green-900">
                If your answer is “yes” to these 3 questions, you may proceed with the pre-approval application!
            </p>

            <div class="mt-8">
                <a href="{{ route('application.form') }}" class="inline-block bg-green-800 hover:bg-green-900 text-white px-8 py-4 rounded-full text-lg font-bold">
                    Get Pre-Approved Now
                </a>
            </div>
        </div>

        <div>
            <img src="{{ asset('images/mortgage-keys.jpg') }}" class="rounded-3xl shadow-xl w-full object-cover" alt="Mortgage key handoff">
        </div>

    </div>
</section>

<section id="apply" class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-6">

        <div class="mb-10">
            <h2 class="text-4xl font-extrabold">Who Can Apply</h2>
            <p class="mt-3 text-lg text-gray-600">
                Mortgage pre-approval is open to qualified individuals, groups, and organizations ready to acquire Nigerian property.
            </p>
        </div>

        <div class="flex gap-6 overflow-x-auto scroll-smooth pb-6 snap-x snap-mandatory">

            @foreach([
                ['🏠', 'Private Citizens', 'For qualified individuals buying a home or investment property in Nigeria.'],
                ['🌍', 'Nigerians in the Diaspora', 'Apply from abroad as long as the property is in Nigeria and you operate a Nigerian bank account.'],
                ['📈', 'Investors/ Entrepreneurs', 'For business owners and investors acquiring properties for portfolios or wealth strategies.'],
                ['🏢', 'Companies & Executives', 'For SMEs operators, executives, and corporate buyers pursuing qualified property acquisition.'],
                ['👥', 'Staff Housing Groups', 'For staff housing arrangements, especially organized groups seeking multiple units.'],
                ['🤝', 'Cooperatives', 'For cooperative societies pooling members together for property acquisition.'],
                ['🏘️', 'Estate/ Block Buyers', 'Groups of 5+ are welcome for blocks of flats, estate units, or grouped property purchases.'],
                ['⚖️', 'Government Officials', 'For verified public-sector earners with steady income and qualifying property documents.']
            ] as [$icon, $title, $text])
                <div class="min-w-[300px] md:min-w-[360px] snap-start bg-[#f7f8f5] rounded-3xl p-8 shadow-sm border border-gray-100">

                    <div class="w-24 h-24 mx-auto rounded-full bg-green-100 flex items-center justify-center text-5xl mb-8">
                        {{ $icon }}
                    </div>

                    <h3 class="text-2xl font-extrabold mb-4 text-center">
                        {{ $title }}
                    </h3>

                    <p class="text-gray-600 leading-relaxed text-base text-center">
                        {{ $text }}
                    </p>

                </div>
            @endforeach

        </div>

    </div>
</section>

<section id="documents" class="py-20 bg-[#f7f8f5]">

    <div class="max-w-7xl mx-auto px-6">

        <!-- SECTION HEADER -->
        <div class="mb-14">
            <h2 class="text-4xl font-extrabold mb-4">
                Document Checklist
            </h2>

            <p class="text-lg text-gray-600">
                For the application to be processed quickly, have these available.
            </p>
        </div>

        <!-- DOCUMENT GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @foreach([
                'Copy of credit report from country of residence (varies from bank to bank)',
                'Evidence of income (12 months account statement + paystubs)',
                'Formal loan application letter indicating repayment source, tenor, and equity contribution',
                'Letter of acceptance of offer from the customer',
                'Letter of sale stating property amount from vendor/seller',
                'Property title documents',
                'Reference letter from customer’s bank in country of residence',
                'Valid international passport/ resident permit'
            ] as $doc)

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex gap-4 items-start">

                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-800 font-bold flex-shrink-0">
                        ✓
                    </div>

                    <div class="text-gray-700 text-lg font-semibold leading-relaxed">
                        {{ $doc }}
                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

<!-- PREPARE TO PAY -->
<section class="bg-white py-20">

    <div class="max-w-7xl mx-auto px-6">

        <div class="mb-14">
            <h2 class="text-4xl font-extrabold mb-4">
                Be Ready To Pay For The Following
            </h2>

            <p class="text-lg text-gray-600">
                This may apply depending on the banking partner, property structure, legal documentation, and mortgage processing requirements.
            </p>
        </div>

        <div class="space-y-5 max-h-[450px] overflow-y-auto pr-3">

            @foreach([
            [
            'title' => 'Down payment of at least 20% of the amount to be borrowed',
            'text' => 'Required'
            ],

            [
            'title' => 'Property valuation',
            'text' => 'Required to determine the market value of the property and ensure it meets lending criteria.'
            ],

            [
            'title' => 'Mortgage protection assurance policy on the life of the borrower',
            'text' => 'This protects the mortgage facility in the event of life.'
            ],

            [
            'title' => 'Facility fees',
            'text' => 'Administrative and processing fees charged during mortgage setup and underwriting.'
            ],

            [
            'title' => 'Insurance premiums',
            'text' => 'Insurance-related costs required by the banking and mortgage partners.'
            ],

            [
            'title' => 'Interest payments for 3 months to be placed in a cash-collateral account',
            'text' => 'Some banks require temporary interest reserves before full disbursement.'
            ],

            [
            'title' => 'Cost of perfection of the title & legal mortgage',
            'text' => 'Ensures the property title and mortgage documentation are properly registered.'
            ],

            [
            'title' => 'Fire and all-perils insurance on property',
            'text' => 'Required'
            ],

            [
            'title' => 'Legal search on the property',
            'text' => 'Required'
            ],

            [
            'title' => 'Tax assessment',
            'text' => 'Government-required property and documentation assessments may apply.'
            ]

            ] as $cost)

                <div class="bg-[#f7f8f5] border-l-8 border-green-800 rounded-2xl p-7 shadow-sm">

                    <div class="flex items-start gap-5">

                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-800 text-xl font-extrabold flex-shrink-0">
                            ₦
                        </div>

                        <div>

                            <h3 class="text-xl font-extrabold text-gray-900">
                                {{ $cost['title'] }}
                            </h3>

                            <p class="mt-2 text-gray-600 leading-relaxed">
                                {{ $cost['text'] }}
                            </p>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

<section id="faq" class="bg-[#f7f8f5] py-20">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-4xl font-extrabold mb-10">Frequently Asked Questions</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach([
                ['Does pre-approval guarantee final mortgage approval?', 'In most cases, yes — only if your income/inflow has been verified and the property has legit title documents.'],
                ['What happens after I upload my receipt?', 'You’ll be paired with a mortgage specialist from a MREIF-affiliated banking partner.'],
                ['How long does the application process take before funds are dispersed?', 'If all documents from all parties are ready and available, our expedited process takes 4–5 weeks flat.'],
                ['How much do I have to make to access the mortgage?', 'It depends on how much you want to access. The rough formula is to divide by 30. If you want ₦100M, your monthly income should be around ₦3.3M minimum. If you want ₦60M, your income should be around ₦2M per month, and so on.'],
                ['Do I have to live in Nigeria to access the mortgage?', 'Not at all. The property must be in Nigeria — you don’t have to be here. You must, however, operate a Nigerian bank account to complete the mortgage process.'],
                ['Do the banks accept collateral?', 'No, collateral is not accepted at this juncture.']
            ] as [$question, $answer])
                <details class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <summary class="cursor-pointer flex justify-between items-center font-bold text-xl leading-snug">
                        {{ $question }}
                        <span class="plus transition-transform text-2xl">+</span>
                    </summary>

                    <p class="mt-4 text-lg text-gray-600 leading-relaxed">
                        {{ $answer }}
                    </p>
                </details>
            @endforeach
        </div>
    </div>
</section>

<section class="py-20 bg-green-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-6">
        <h2 class="text-4xl font-extrabold mb-5">
            Ready to Secure Your Mortgage Pre-Approval?
        </h2>

        <p class="text-lg text-green-100 mb-8">
            Start your secure assessment and submit your documents for review.
        </p>

        <a href="{{ route('application.form') }}" class="inline-block bg-white text-green-900 px-8 py-4 rounded-full text-lg font-bold">
            Start Secure Application
        </a>
    </div>
</section>

<footer class="bg-white py-10">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between gap-6 text-sm text-gray-600">
        <div>
            <img src="{{ asset('images/nigeria-mortgage-logo-cthru.png') }}" class="h-12 mb-3" alt="Nigeria Mortgages">
            <p>Secure mortgage pre-approval for Nigerian property buyers.</p>
        </div>

        <div>
            <p class="font-bold text-gray-900">In association with</p>
            <p>Power Players · MOFI · MRIEF · ARM</p>
        </div>

        <div>
            <p>© {{ date('Y') }} Nigeria Mortgages</p>
        </div>
    </div>
</footer>

</body>
</html>