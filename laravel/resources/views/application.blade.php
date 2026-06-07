<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Nigeria Mortgages</title>
            <script src="https://cdn.tailwindcss.com"></script>
            @include('partials.meta-pixel')
            @if(config('services.meta.pixel_id'))
                <script>
                    fbq('trackCustom', 'ApplicationPageViewed');
                </script>
            @endif
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
                    font-size: 1rem;
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
            <body class="bg-[#f7f8f5] text-gray-900 antialiased">
                
                <div class="min-h-screen flex flex-col">
                    
                    <!-- NAVBAR -->
                    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">

                        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

                            <a href="{{ route('home') }}">
                                <img
                                    src="{{ asset('images/nigeria-mortgage-logo-cthru.png') }}"
                                    class="h-12 w-auto"
                                    alt="Nigeria Mortgages"
                                >
                            </a>

                            <a
                                href="{{ route('home') }}"
                                class="text-sm md:text-base font-bold text-green-800 hover:text-green-900"
                            >
                                Back To Homepage
                            </a>

                        </div>

                    </header>

                    <!-- TOP INSTRUCTION BAR -->
                    <section class="bg-green-800 text-white">

                        <div class="max-w-5xl mx-auto px-6 py-6 text-center">

                            <h1 class="text-2xl md:text-3xl font-extrabold">
                                Secure 2-Minute Pre-Approval Application
                            </h1>

                            <p class="mt-3 text-sm text-green-100 leading-snug">
                                Please complete this form as accurately as possible. All information provided will be used to assess your mortgage facility eligibility for a property in Nigeria. Submission of this form is for pre-approval assessment only and <strong> does not constitute a binding mortgage offer</strong>. <br>Final approval remains subject to full underwriting, verification of all documents, and applicable terms and conditions.
                            </p>

                        </div>

                    </section>
                    



                    <!-- Main content -->
                    <main class="flex-1 py-8 px-4">
                        <div class="max-w-2xl mx-auto">
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


                                <!-- STEPPER -->
                                <div class="mb-10">

                                    <div class="flex items-center justify-between gap-4">

                                        <div class="step-item flex-1" data-step="1">
                                            <div class="step-circle bg-green-800 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold mx-auto">
                                                1
                                            </div>

                                            <p class="text-center mt-3 font-semibold text-base">
                                                Personal Data
                                            </p>
                                        </div>

                                        <div class="h-1 bg-gray-300 flex-1"></div>

                                        <div class="step-item flex-1" data-step="2">
                                            <div class="step-circle bg-gray-300 text-gray-700 w-12 h-12 rounded-full flex items-center justify-center font-bold mx-auto">
                                                2
                                            </div>

                                            <p class="text-center mt-3 font-semibold text-base">
                                                Work Information
                                            </p>
                                        </div>

                                        <div class="h-1 bg-gray-300 flex-1"></div>

                                        <div class="step-item flex-1" data-step="3">
                                            <div class="step-circle bg-gray-300 text-gray-700 w-12 h-12 rounded-full flex items-center justify-center font-bold mx-auto">
                                                3
                                            </div>

                                            <p class="text-center mt-3 font-semibold text-base">
                                                Property Information
                                            </p>
                                        </div>

                                        <div class="h-1 bg-gray-300 flex-1"></div>

                                        <div class="step-item flex-1" data-step="4">
                                            <div class="step-circle bg-gray-300 text-gray-700 w-12 h-12 rounded-full flex items-center justify-center font-bold mx-auto">
                                                4
                                            </div>

                                            <p class="text-center mt-3 font-semibold text-base">
                                                Upload Documents
                                            </p>
                                        </div>

                                    </div>

                                </div>
                                
                                
                                <form
                                    action="{{ route('application.submit') }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    class="bg-white rounded-xl shadow-md p-4 md:p-6 space-y-5">
                                    
                                    @csrf
                                    
                                    <div class="form-step" data-step="1">
                                        <!-- 1. PERSONAL DATA -->
                                        <div>
                                            <div class="section-title text-red-500 text-lg -mt-2 mb-4">Personal Data</div>

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
                                    </div>
                                    
                                    <div class="form-step hidden" data-step="2">
                                        <!-- 2. WORK INFORMATION -->
                                        <div>
                                            <div class="section-title text-red-500 text-lg -mt-2 mb-4">Work Information</div>
                                        
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
                                    </div>

                                    
                                    <div class="form-step hidden" data-step="3">
                                        <!-- 3. PROPERTY INFORMATION -->
                                        <div>
                                            <div class="section-title text-red-500 text-lg -mt-2 mb-4">Property Information</div>
                                        
                                            
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
                                    </div>

                                    <div class="form-step hidden" data-step="4">    
                                            <!-- 4. Document Uploads -->
                                            <div id="SUPPORTING_DOCUMENTS_SINGLE_BUTTON_BLOCK">
                                                <div class="section-title text-red-500 text-lg -mt-2 mb-4">See Document Checklist</div>
                                            
                                                <label for="supporting_documents" class="label-style">
                                                    Upload supporting documents (PDF, JPG, PNG, ZIP, RAR)
                                                </label>
                                            
                                                <input
                                                    type="file"
                                                    id="supporting_documents"
                                                    name="supporting_documents[]"
                                                    class="block w-full text-xs sm:text-sm text-gray-700"
                                                    accept="application/pdf,image/png,image/jpeg,.zip,.rar"
                                                    multiple
                                                >
                                                <hr class="my-4 border-gray-300">
                                                <small class="text-xs text-gray-700 leading-snug block mt-1">
                                                    Having trouble? You may also download the mortgage pre-approval form, complete it manually, and email it with your supporting documents to <strong>pre-approval@nigeriamortgages.com</strong>.
                                                </small>

                                                <div class="mt-4">
                                                    <a
                                                        href="{{ asset('documents/nigeria-mortgages-pre-approval.pdf') }}"
                                                        download
                                                        class="inline-flex items-center px-5 py-3 bg-green-800 text-sm hover:bg-green-900 text-white rounded-xl font-semibold transition"
                                                    >
                                                        Download Mortgage Application Form (optional)
                                                    </a>
                                                </div>
                                            <hr class="my-4 border-gray-300">
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
                                            <div class="pt-4 space-y-3">
                                                @error('privacy')
                                                    <div class="error-text">{{ $message }}</div>
                                                @enderror
                                           
                                                    <br>
                                                <div class="mt-2">
                                                    {!! NoCaptcha::display() !!}
                                                    @error('g-recaptcha-response')
                                                        <div class="error-text">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                   
                                                    <br>
                                                    
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
                                                
                                            </div>
                                        

                                                                
                                        <!-- SUBMIT -->
                                        

                                    </div>
                                    
                                    
                                    
                                    <div id="buttonRow" class="pt-10 grid grid-cols-3 items-center gap-4">

                                        <button
                                            type="button"
                                            id="prevBtn"
                                            class="hidden justify-self-start bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold"
                                        >
                                            Previous
                                        </button>

                                        <button
                                            type="button"
                                            id="resetBtn"
                                            class="justify-self-center bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-xl font-semibold text-sm"
                                        >
                                            Start Over
                                        </button>

                                        <button
                                            type="button"
                                            id="nextBtn"
                                            class="justify-self-end bg-green-800 hover:bg-green-900 text-white px-8 py-3 rounded-lg font-bold"
                                        >
                                            Next
                                        </button>

                                        <button
                                            type="submit"
                                            id="submitBtn"
                                            class="hidden justify-self-end bg-blue-700 hover:bg-blue-800 text-white px-8 py-3 rounded-lg font-bold"
                                        >
                                            Continue To Secure Payment
                                        </button>

                                    </div>   
                                </form>

                                <div class="text-center mt-6 max-w-3xl mx-auto">

                                    <p class="text-sm text-gray-600 leading-snug">
                                        By completing and submitting this form, you consent to your information being shared securely with our mortgage partners solely for the purpose of assessing your application.
                                    </p>

                                </div>
                            </section>
                        </div>

                        <!-- Logos footer -->
                        
                    </main>
                    <footer class="bg-white py-10">
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
                </div>
                
                <script>
                    // SUPPORTING_DOCUMENTS_SINGLE_BUTTON_SCRIPT
                    document.addEventListener('DOMContentLoaded', function () {
                        const input = document.getElementById('supporting_documents');
                        const listWrapper = document.getElementById('selected-files-wrapper');
                        const list = document.getElementById('selected-files');
                    
                        // This holds ALL files the user has picked so far
                        window.selectedFiles = [];
                    
                        if (!input) {
                            return; // safety: if the field isn't on this page, do nothing
                        }
                    
                        function refreshFileInput() {
                            const dataTransfer = new DataTransfer();

                            window.selectedFiles.forEach((file) => {
                                dataTransfer.items.add(file);
                            });

                            input.files = dataTransfer.files;

                            list.innerHTML = '';

                            window.selectedFiles.forEach((file, index) => {
                                const li = document.createElement('li');

                                li.className =
                                    'flex items-center justify-between gap-3 bg-gray-100 rounded-lg px-3 py-2';

                                const span = document.createElement('span');
                                span.textContent = file.name;

                                const removeBtn = document.createElement('button');

                                removeBtn.type = 'button';
                                removeBtn.textContent = 'Remove';

                                removeBtn.className = 'text-red-600 font-semibold text-xs';

                                removeBtn.addEventListener('click', function () {
                                    window.selectedFiles.splice(index, 1);
                                    refreshFileInput();
                                });

                                li.appendChild(span);
                                li.appendChild(removeBtn);

                                list.appendChild(li);
                            });

                            if (window.selectedFiles.length > 0) {
                                listWrapper.classList.remove('hidden');
                            } else {
                                listWrapper.classList.add('hidden');
                            }
                        }

                        input.addEventListener('change', function () {
                            Array.from(input.files).forEach((file) => {
                                window.selectedFiles.push(file);
                            });

                            refreshFileInput();
                        });
                    });
                    </script>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            let currentStep = 1;
                            const totalSteps = 4;
                            const steps = document.querySelectorAll('.form-step');
                            const nextBtn = document.getElementById('nextBtn');
                            const prevBtn = document.getElementById('prevBtn');
                            const submitBtn = document.getElementById('submitBtn');
                            const resetBtn = document.getElementById('resetBtn');
                            const circles = document.querySelectorAll('.step-circle');
                            function showStep(step) {
                                steps.forEach((section) => {
                                    section.classList.add('hidden');
                                    if (parseInt(section.dataset.step) === step) {
                                        section.classList.remove('hidden');
                                    }
                                });
                                circles.forEach((circle, index) => {
                                    if ((index + 1) <= step) {
                                        circle.classList.remove('bg-gray-300', 'text-gray-700');
                                        circle.classList.add('bg-green-800', 'text-white');
                                    } else {
                                        circle.classList.remove('bg-green-800', 'text-white');
                                        circle.classList.add('bg-gray-300', 'text-gray-700');
                                    }
                                });
                                   
                                if (step === totalSteps) {
                                    nextBtn.classList.add('hidden');
                                    submitBtn.classList.remove('hidden');
                                } else {
                                    nextBtn.classList.remove('hidden');
                                    submitBtn.classList.add('hidden');
                                }
                                resetBtn.classList.remove('justify-self-start');
                                resetBtn.classList.add('justify-self-center');

                                const buttonRow = document.getElementById('buttonRow');

                                    buttonRow.classList.remove('grid-cols-2');
                                    buttonRow.classList.add('grid-cols-3');

                                    if (step === 1) {

                                        prevBtn.classList.remove('hidden');
                                        prevBtn.classList.add('opacity-0');
                                        prevBtn.classList.add('pointer-events-none');

                                        resetBtn.classList.remove('justify-self-start', 'justify-self-end');
                                        resetBtn.classList.add('justify-self-center');

                                        nextBtn.classList.remove('justify-self-center', 'justify-self-start');
                                        nextBtn.classList.add('justify-self-end');

                                    } else {

                                        prevBtn.classList.remove('hidden');
                                        prevBtn.classList.remove('opacity-0');
                                        prevBtn.classList.remove('pointer-events-none');

                                        resetBtn.classList.remove('justify-self-start', 'justify-self-end');
                                        resetBtn.classList.add('justify-self-center');

                                    }
                            }
                            nextBtn.addEventListener('click', function () {
                                if (currentStep < totalSteps) {
                                    currentStep++;
                                    showStep(currentStep);
                                }
                            });
                            prevBtn.addEventListener('click', function () {
                                if (currentStep > 1) {
                                    currentStep--;
                                    showStep(currentStep);
                                }
                            });
                            
                            resetBtn.addEventListener('click', function () {

                                if (confirm('Clear this screen only?')) {

                                    const currentSection = document.querySelector(
                                        `.form-step[data-step="${currentStep}"]`
                                    );

                                    const inputs = currentSection.querySelectorAll(
                                        'input, textarea, select'
                                    );

                                    inputs.forEach((input) => {

                                        if (
                                            input.type === 'checkbox' ||
                                            input.type === 'radio'
                                        ) {
                                            input.checked = false;
                                        } else {
                                            input.value = '';
                                        }
                                    });

                                    const selectedFilesList = document.getElementById('selected-files');
                                    const selectedFilesWrapper = document.getElementById('selected-files-wrapper');
                                    const supportingDocuments = document.getElementById('supporting_documents');

                                    if (currentStep === 4) {

                                        window.selectedFiles = [];

                                        if (selectedFilesList) {
                                            selectedFilesList.innerHTML = '';
                                        }

                                        if (selectedFilesWrapper) {
                                            selectedFilesWrapper.classList.add('hidden');
                                        }

                                        if (supportingDocuments) {
                                            supportingDocuments.value = '';
                                        }
                                    }
                                }
                            });
                            showStep(currentStep);
                        });
                    </script>


            </body>
    </html>
