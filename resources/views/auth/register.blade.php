<x-guest-layout>
    {{-- Container الرئيسي للخلفية والتركيز --}}
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat relative"
        style="background-image: url('{{ asset('images/bg-login.jpg.jpeg') }}');">

        <div class="absolute inset-0 bg-black/40"></div>

        {{-- التعديل 1: تقليل العرض الأقصى من max-w-xl إلى max-w-md --}}
          <div class="relative z-10 w-full max-w-lg mx-4">
            {{-- Card الفورم المعدَّل: تقليل الهامش الداخلي من p-10 إلى p-8 --}}
            <div class="backdrop-blur-3xl border border-white/20 rounded-[30px] 
                         bg-gradient-to-br from-gray-900/80 via-black/90 to-green-900/80 
                         shadow-2xl shadow-black/90 p-8">

                {{-- اللوجو والعنوان --}}
                <div class="text-center mb-6">
                    <div class="mx-auto w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl 
                                 flex items-center justify-center shadow-lg ring-2 ring-gray-400/80 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>

                  <h2 class="text-4xl font-extrabold text-white tracking-widest"
                        style="-webkit-text-stroke: 1px rgba(255, 255, 255, 0.4); text-stroke: 1px rgba(255, 255, 255, 0.4);">
                        UniSmart
                    </h2>

                    <p class="mt-1 text-white/70 text-sm">
                        College Facility Management System
                    </p>
                </div>

                {{-- عرض أخطاء التحقق (Validation Errors) --}}
                <x-validation-errors class="mb-5 text-red-400" />

                <form method="POST" action="{{ route('register') }}" class="space-y-5" id="registerForm">
                    @csrf

                    {{-- Header --}}
                    <div class="text-center mb-6">
                        <h4 class="text-2xl font-bold text-white mb-1">
                            {{ __('Create Account') }}
                        </h4>
                        <p class="text-sm text-white/70">
                            {{ __('Join our college management system') }}
                        </p>
                    </div>

                    {{-- Role Selection - Cards Style --}}
                    <div class="flex flex-col items-center space-y-4">
                        <div class="flex justify-center space-x-4">
                            {{-- Student Card (تقليل الـ padding من p-4 إلى p-3) --}}
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="student" class="hidden peer" />
                                <div class="flex flex-col items-center p-3 border-2 rounded-lg 
                                            peer-checked:border-green-400 peer-checked:bg-green-900/40 
                                            border-white/30 transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 21.75a11.952 11.952 0 01-6.825-3.693 12.083 12.083 0 01.665-6.479L12 14z" />
                                    </svg>
                                    <span class="text-green-400 font-semibold text-sm">Student</span>
                                </div>
                            </label>

                            {{-- Professor Card (تقليل الـ padding من p-4 إلى p-3) --}}
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="professor" class="hidden peer" />
                                <div class="flex flex-col items-center p-3 border-2 rounded-lg 
                                            peer-checked:border-green-400 peer-checked:bg-green-900/40
                                            border-white/30 transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-green-400 font-semibold text-sm">Professor</span>
                                </div>
                            </label>
                        </div>

                        {{-- الحقول التي يتم التحكم بظهورها وإخفائها --}}
                        <div id="role-dependent-fields" class="w-full space-y-4">
                            
                            {{-- Department Select --}}
                            <div id="department-container" class="hidden">
                                <x-label for="department_id" value="Select the Section" class="text-white/70 font-semibold mb-1" />
                                <select id="department_id" name="department_id" 
                                    class="block w-full px-5 py-3 rounded-xl bg-white/10 border border-white/10 text-white placeholder-white/50
                                           focus:outline-none focus:ring-2 focus:ring-green-400/40 focus:border-green-300
                                           text-base font-semibold shadow-inner" required>
                                    <option value="" class="bg-gray-800 text-white">Select section</option>
                                    @if(isset($departments))
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" class="bg-gray-800 text-white">{{ $department->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            {{-- Year Select --}}
                            <div id="year-container" class="hidden">
                                <x-label for="year" value="Select Year" class="text-white/70 font-semibold mb-1" />
                                <select id="year" name="year" 
                                    class="block w-full px-5 py-3 rounded-xl bg-white/10 border border-white/10 text-white placeholder-white/50
                                           focus:outline-none focus:ring-2 focus:ring-green-400/40 focus:border-green-300
                                           text-base font-semibold shadow-inner" required>
                                    <option value="" class="bg-gray-800 text-white">Select year</option>
                                    <option value="first" class="bg-gray-800 text-white">First</option>
                                    <option value="second" class="bg-gray-800 text-white">Second</option>
                                    <option value="third" class="bg-gray-800 text-white">Third</option>
                                    <option value="fourth" class="bg-gray-800 text-white">Fourth</option>
                                    <option value="fifth" class="bg-gray-800 text-white">Fifth</option>
                                </select>
                            </div>

                            {{-- Verification Code --}}
                            <div id="verification-code-container" class="hidden">
                                <x-label for="verification_code" value="{{ __('Verification Code') }}" class="text-white/70 font-semibold mb-1" />
                                <x-input 
                                    id="verification_code" 
                                    class="block w-full px-5 py-3 rounded-xl bg-white/10 border border-white/10 text-white placeholder-white/50
                                           focus:outline-none focus:ring-2 focus:ring-green-400/40 focus:border-green-300
                                           text-base font-semibold shadow-inner" 
                                    type="text" 
                                    name="verification_code" 
                                    placeholder="Enter verification code"
                                />
                            </div>
                        </div>
                    </div>

                    <hr class="border-white/10 my-4" />

                    {{-- Name Field (تقليل الـ padding في الحقل من py-4 إلى py-3) --}}
                    <div>
                        <x-label for="name" value="{{ __('Full Name') }}" class="text-white/70 font-semibold mb-1 text-sm" />
                        <x-input 
                            id="name" 
                            class="block w-full px-5 py-3 rounded-xl bg-white/10 border border-white/10 text-white placeholder-white/50
                                   focus:outline-none focus:ring-2 focus:ring-green-400/40 focus:border-green-300
                                   text-base font-semibold shadow-inner" 
                            type="text" 
                            name="name" 
                            :value="old('name')" 
                            required 
                            autofocus 
                            autocomplete="name"
                            placeholder="Enter your full name"
                        />
                    </div>

                    {{-- Email Field --}}
                    <div>
                        <x-label for="email" value="{{ __('Email Address') }}" class="text-white/70 font-semibold mb-1 text-sm" />
                        <x-input 
                            id="email" 
                            class="block w-full px-5 py-3 rounded-xl bg-white/10 border border-white/10 text-white placeholder-white/50
                                   focus:outline-none focus:ring-2 focus:ring-green-400/40 focus:border-green-300
                                   text-base font-semibold shadow-inner" 
                            type="email" 
                            name="email" 
                            :value="old('email')" 
                            required 
                            autocomplete="username"
                            placeholder="Enter your email"
                        />
                    </div>

                    {{-- Password Field --}}
                    <div>
                        <x-label for="password" value="{{ __('Password') }}" class="text-white/70 font-semibold mb-1 text-sm" />
                        <x-input 
                            id="password" 
                            class="block w-full px-5 py-3 rounded-xl bg-white/10 border border-white/10 text-white placeholder-white/50
                                   focus:outline-none focus:ring-2 focus:ring-green-400/40 focus:border-green-300
                                   text-base font-semibold shadow-inner" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            placeholder="Create a password"
                        />
                    </div>

                    {{-- Confirm Password Field --}}
                    <div>
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-white/70 font-semibold mb-1 text-sm" />
                        <x-input 
                            id="password_confirmation" 
                            class="block w-full px-5 py-3 rounded-xl bg-white/10 border border-white/10 text-white placeholder-white/50
                                   focus:outline-none focus:ring-2 focus:ring-green-400/40 focus:border-green-300
                                   text-base font-semibold shadow-inner" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="Confirm your password"
                        />
                    </div>

                    {{-- Terms and Privacy Policy --}}
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="flex items-center pt-2">
                            <x-checkbox name="terms" id="terms" required class="rounded text-green-600 focus:ring-green-500 bg-white/10 border-white/30" />
                            <div class="ml-2 text-sm text-white/70">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-green-400 hover:text-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-green-400 hover:text-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Buttons --}}
                    <div class="flex items-center justify-between pt-4">
                        <a class="font-bold text-green-400 hover:text-green-300 underline decoration-green-400 text-sm" href="{{ route('login') }}">
                            {{ __('Already registered?') }}
                        </a>

                        <button type="submit"
                            class="py-3 px-8 rounded-xl text-white font-bold text-lg
                                   bg-gradient-to-r from-green-600 to-green-800
                                   hover:from-green-500 hover:to-green-700
                                   shadow-lg transform transition hover:scale-[1.01] duration-300">
                            {{ __('Register') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    
    {{-- جزء الـ JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const professorRadio = document.querySelector('input[name="role"][value="professor"]');
            const studentRadio = document.querySelector('input[name="role"][value="student"]');
            const verificationCodeContainer = document.getElementById('verification-code-container');
            const departmentContainer = document.getElementById('department-container');
            const yearContainer = document.getElementById('year-container'); 

            function toggleFields() {
                // إخفاء جميع الحقول المتعلقة بالدور أولاً
                verificationCodeContainer.classList.add('hidden');
                document.getElementById('verification_code').removeAttribute('required');
                
                departmentContainer.classList.add('hidden');
                document.getElementById('department_id').removeAttribute('required');
                
                yearContainer.classList.add('hidden');
                document.getElementById('year').removeAttribute('required');

                // إظهار الحقول المناسبة حسب الدور المُختار
                if (professorRadio.checked) {
                    verificationCodeContainer.classList.remove('hidden');
                    document.getElementById('verification_code').setAttribute('required', 'required');
                } else if (studentRadio.checked) {
                    departmentContainer.classList.remove('hidden');
                    document.getElementById('department_id').setAttribute('required', 'required');
                    
                    yearContainer.classList.remove('hidden');
                    document.getElementById('year').setAttribute('required', 'required');
                }
            }

            // الاستماع لتغييرات اختيار الدور
            professorRadio.addEventListener('change', toggleFields);
            studentRadio.addEventListener('change', toggleFields);

            // تهيئة حالة الحقول عند تحميل الصفحة
            toggleFields();
        });
    </script>
</x-guest-layout>