<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nigeria Mortgages</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f7fafc; }
        .hero-background {
            background-color: #156b2e; /* solid deep navy */
        }

        .input-style {
            border-radius: 0.5rem;
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            font-size: 0.95rem;
        }
        .input-style:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.5);
        }
        .label-style {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }
        .error-text {
            color: #b91c1c;
            font-size: 0.8rem;
            margin-top: 0.15rem;
        }
        .section-title {
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #4b5563;
            margin-bottom: 0.5rem;
        }
        .form-grid-2 {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 0.75rem 1rem;
        }
        @media (min-width: 768px) {
            .form-grid-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        .number-input {
            text-align: right;
        }
        .footer-note {
            font-size: 0.75rem;
            color: #4b5563;
            line-height: 1.4;
        }
    </style>

    {!! NoCaptcha::renderJs() !!}
</head>
<body class="antialiased">
    
    <div class="min-h-screen flex flex-col">
        <!-- Top hero -->
        <header class="hero-background text-white px-4">
            <div class="max-w-5xl mx-auto text-center py-16 sm:py-20 px-4 sm:px-8">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-wide">
                    SELF-CERTIFY & QUALIFY TO GET PRE-APPROVED! <br> SECURE UP TO ₦100M!
                </h1>
            </div>
        </header>



        <!-- Main content -->
        <main class="flex-1 py-8 px-4">
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left column: text mirroring PDF tone -->
                <section class="space-y-6">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-gray-900">
                        Secure 2-Minute Pre-Approval Application <br>
                    </h2>
                    
                    <div class="text-sm text-gray-700 leading-relaxed">
                        Don’t be one of those future people who'll say, “I should have…" <br>Get pre-approved! <br>
                        Secure the funds for the mortgage today; reap the benefits tomorrow!
                    </div>
                    <!-- Key Points with Icons -->
                    <div class="text-sm text-gray-700 leading-relaxed">
                        <p><span class="text-gray-700 font-semibold">📌</span> Free up the capital you need to build your investment portfolio!</p>
                        <p><span class="text-gray-700 font-semibold">⏱️</span> We fight hard to ensure expedited processing.</p>
                        <p><span class="text-gray-700 font-semibold">⏱️</span> This opportunity is time-sensitive.</p>
                        <p><span class="text-gray-700 font-semibold">✅</span> Our partners are on standby to process your applications. They always prioritize serious investors and future homeowners ready for acquisition.</p>
                    </div>
                
                    <!-- Application Instructions -->
                    <!-- Document Checklist -->
                    <h2 class="text-2xl font-bold text-gray-900">
                        Document Checklist
                    </h2>
                    <ol class="text-xs font-semibold sm:text-sm text-gray-700 space-y-1 list-decimal pl-5 mb-4">
                        <li>3 most recent pay slips / salary slips.</li>
                        <li>Last 3 months’ bank statements.</li>
                        <li>Data page of international passport.</li>
                        <li>Certificate of Ownership (C. of O.) for property of interest.</li>
                    </ol>
                
                    <!-- Disclaimers -->
                    <div class="text-[11px] sm:text-xs text-gray-500 mt-4 leading-relaxed space-y-2">
                        <p>
                            Please complete this form as accurately as possible. All information you provide will be used to assess your eligibility for a mortgage facility for property in Nigeria. Submission of this form is for pre-approval assessment only and <span class="font-semibold">does not constitute a binding mortgage offer</span>. Final approval remains subject to full underwriting, verification of all documents, and applicable terms and conditions.
                        </p>
                        <p>
                            By completing and submitting this form, you consent to your information being shared securely with our mortgage partners solely for the purpose of assessing your application.
                        </p>
                    </div>
                    
                    <section class="space-y-6" justify-center>
                        <img src="/images/nigeria-mortgage-logo-cthru.png" class="h-100 w-auto" alt="Nigeria Mortgages Logo">
                    </section>
                </section>
                
                
                
                


                <!-- Right column: form matching ApplicantController fields -->
                <section>
                    @if(session('success_message'))
                        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                            {{ session('success_message') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                            <p class="font-semibold mb-1">Please fix the following and submit again:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form
                        action="{{ route('application.submit') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="bg-white rounded-xl shadow-md p-4 md:p-6 space-y-5"
                    >
                        @csrf
                        <!-- 1. PERSONAL DATA -->
                        <div>
                            <div class="section-title text-red-500">Personal Data</div>

                            <div class="form-grid-2">
                                <div>
                                    <label for="first_name" class="label-style">First Name</label>
                                    <input
                                        type="text"
                                        id="first_name"
                                        name="first_name"
                                        placeholder="Add first name"
                                        class="input-style"
                                        value="{{ old('first_name') }}"
                                        required
                                    >
                                    @error('first_name')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="last_name" class="label-style">Last Name</label>
                                    <input
                                        type="text"
                                        id="last_name"
                                        name="last_name"
                                        placeholder="Add last name"
                                        class="input-style"
                                        value="{{ old('last_name') }}"
                                        required
                                    >
                                    @error('last_name')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="address" class="label-style">Address (if outside of Nigeria, add country in parenthesis)</label>
                                <input
                                    type="text"
                                    id="address"
                                    placeholder="1600 Pennsylvania Ave NW (United States)"
                                    name="address"
                                    class="input-style"
                                    value="{{ old('address') }}"
                                    required
                                >
                                @error('address')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            
                            <div class="form-grid-2 mt-3">
                                <div>
                                    <label for="city" class="label-style">City</label>
                                    <input
                                        type="text"
                                        id="city"
                                        class="input-style"
                                        placeholder="Add city/ town"
                                        name="city"
                                        value="{{ old('city') }}"
                                    >
                                    @error('city')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="state" class="label-style">State</label>
                                    <input
                                        type="text"
                                        id="state"
                                        class="input-style"
                                        placeholder="Add state"
                                        name="state"
                                        value="{{ old('state') }}"
                                    >
                                    @error('state')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-grid-2 mt-3">
                                <div>
                                    <label for="email" class="label-style">Email</label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        placeholder="mr.president@nigeria.com"
                                        class="input-style"
                                        value="{{ old('email') }}"
                                        required
                                    >
                                    @error('email')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone_number" class="label-style">Phone Number w/ country code</label>
                                    <input
                                        type="tel"
                                        id="phone_number"
                                        name="phone_number"
                                        placeholder="+1-718-222-2222"
                                        class="input-style number-input"
                                        value="{{ old('phone_number') }}"
                                        required
                                    >
                                    @error('phone_number')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 2. WORK INFORMATION -->
                        <div class="pt-4 border-t border-gray-200">
                            <div class="section-title text-red-500">Work Information</div>
                        
                            <div class="form-grid-2">
                                <div>
                                    <label for="company_name" class="label-style">Company Name</label>
                                    <input
                                        type="text"
                                        id="company_name"
                                        name="company_name"
                                        placeholder="ABC Limited"
                                        class="input-style"
                                        value="{{ old('company_name') }}"
                                    >
                                    @error('company_name')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="years_employed" class="label-style">Years Employed</label>
                                    <input
                                        type="number"
                                        id="years_employed"
                                        name="years_employed"
                                        placeholder="5"
                                        class="input-style number-input"
                                        value="{{ old('years_employed') }}"
                                        min="0"
                                    >
                                    @error('years_employed')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        
                            <div class="form-grid-2 mt-3">
                                <div>
                                    <label for="occupation" class="label-style">Occupation</label>
                                    <input
                                        type="text"
                                        id="occupation"
                                        name="occupation"
                                        placeholder="Add your occupation"
                                        class="input-style"
                                        value="{{ old('occupation') }}"
                                    >
                                    @error('occupation')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="title" class="label-style">Title</label>
                                    <input
                                        type="text"
                                        id="title"
                                        name="title"
                                        placeholder="Manager"
                                        class="input-style"
                                        value="{{ old('title') }}"
                                    >
                                    @error('title')
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- 3. PROPERTY INFORMATION -->
                        <div class="pt-4 border-t border-gray-200">
                            <div class="section-title text-red-500">Property Information</div>
                        
                            
                            <div class="form-grid-2">
                                <!-- AGENT-NAME -->
                                <div>
                                    <label for="agent_name" class="label-style">Agent Name</label>
                                    <input 
                                        type="text" 
                                        name="agent_name" 
                                        id="agent_name"
                                        class="input-style"
                                        placeholder="Leave blank if no agent"
                                        value="{{ old('agent_name') }}"
                                    />
                                </div>
                                
                                
                                <!-- ESTATE-NAME -->
                                <div>
                                    <label for="estate_name" class="label-style">Estate Name</label>
                                    <input
                                        type="text"
                                        id="estate_name"
                                        name="estate_name"
                                        placeholder="ABC Estate Lekki"
                                        class="input-style"
                                        value="{{ old('estate_name') }}"
                                    >
                                </div>
                            </div>
                            
                            
                            <!-- Property Addy -->
                            <div class="mt-3">
                                <label for="property_address" class="label-style">Property Address</label>
                                <textarea
                                    id="property_address"
                                    name="property_address"
                                    placeholder="Plot 78 Yarima Road - Lekki, Lagos"
                                    class="input-style"
                                    rows="2"
                                >{{ old('property_address') }}</textarea>
                                @error('property_address')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                        
                        
                            <!-- Cost of Property -->                        
                            <div class="mt-3">
                                <label for="property_cost" class="label-style">Cost of Property (₦)</label>
                                <input
                                    type="number"
                                    id="property_cost"
                                    name="property_cost"
                                    placeholder="ex: 300,000,000"
                                    class="input-style number-input"
                                    value="{{ old('property_cost') }}"
                                    min="0"
                                >
                                @error('property_cost')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                                <p class="footer-note mt-1">
                                    Indicate the estimated cost of the property in Nigerian Naira (₦).
                                </p>
                            </div>
                        </div>
                        
                        <!-- 4. Document Uploads -->
                        <div class="pt-4 border-t border-gray-200" id="SUPPORTING_DOCUMENTS_SINGLE_BUTTON_BLOCK">
                            <div class="section-title text-red-500">See Document Checklist</div>
                        
                            <label for="supporting_documents" class="label-style">
                                Upload supporting documents (PDF, JPG, PNG)
                            </label>
                        
                            <input
                                type="file"
                                id="supporting_documents"
                                name="supporting_documents[]"
                                class="block w-full text-xs sm:text-sm text-gray-700"
                                accept="application/pdf,image/png,image/jpeg"
                                multiple
                            >
                            <small class="text-[11px] text-gray-500">
                                You can choose several files at once, or click this button multiple times to keep adding more documents.
                            </small>
                        
                            <!-- Selected files list -->
                            <div id="selected-files-wrapper" class="mt-3 hidden">
                                <p class="text-[11px] text-gray-600 mb-1">
                                    Files selected:
                                </p>
                                <ul
                                    id="selected-files"
                                    class="text-[11px] text-gray-700 list-disc list-inside space-y-0.5"
                                ></ul>
                            </div>
                        </div>

                        
                        
                        
                        
                        
                        <!-- 5. DECLARATION, PRIVACY & RECAPTCHA -->
                        <div class="pt-4 border-t border-gray-200 space-y-3">
                            <label class="inline-flex items-start gap-2 text-xs text-gray-700">
                                <input
                                    type="checkbox"
                                    name="privacy"
                                    value="1"
                                    class="mt-1"
                                    {{ old('privacy') ? 'checked' : '' }}
                                    required
                                >
                                <span>
                                    I hereby declare that the information provided above is true and correct to the best of my knowledge.
                                    I understand that this form is for <strong>pre-approval assessment only</strong> and does not guarantee
                                    final approval or disbursement of any mortgage facility.
                                </span>
                            </label>
                            @error('privacy')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        
                            <div class="mt-2">
                                {!! NoCaptcha::display() !!}
                                @error('g-recaptcha-response')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- SUBMIT -->
                        <div class="pt-4 border-t border-gray-200 flex items-center justify-between gap-3">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-5 py-2.5 transition"
                            >
                                Submit Secure Pre-Approval
                            </button>
                            <p class="footer-note text-right">
                                A confirmation may be sent to the email address you provided.
                            </p>
                        </div>

                        
                        
                        
                    </form>
                </section>
            </div>

            <!-- Logos footer -->
            <div class="max-w-6xl mx-auto mt-10 border-t border-gray-200 pt-4">
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
        </main>
    </div>
    <script>
        // SUPPORTING_DOCUMENTS_SINGLE_BUTTON_SCRIPT
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('supporting_documents');
            const listWrapper = document.getElementById('selected-files-wrapper');
            const list = document.getElementById('selected-files');
        
            // This holds ALL files the user has picked so far
            const dataTransfer = new DataTransfer();
        
            if (!input) {
                return; // safety: if the field isn't on this page, do nothing
            }
        
            input.addEventListener('change', function () {
                // Add newly selected files to the running list
                for (const file of input.files) {
                    dataTransfer.items.add(file);
                }
        
                // Tell the input to use the full combined list
                input.files = dataTransfer.files;
        
                // Update the visible list on the page
                list.innerHTML = '';
                for (const file of dataTransfer.files) {
                    const li = document.createElement('li');
                    li.textContent = file.name;
                    list.appendChild(li);
                }
        
                // Show the wrapper once at least one file exists
                if (dataTransfer.files.length > 0) {
                    listWrapper.classList.remove('hidden');
                }
            });
        });
        </script>

</body>
</html>
