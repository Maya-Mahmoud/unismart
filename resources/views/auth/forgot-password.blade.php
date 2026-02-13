<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat relative"
        style="background-image: url('{{ asset('images/bg-login.jpg.jpeg') }}');">

        <div class="absolute inset-0 bg-black/40"></div>

        {{-- الكارد الأوسط بنفس ستايل صفحات التسجيل والدخول --}}
        <div class="relative z-10 w-full max-w-lg mx-4"> 

             <div class="backdrop-blur-3xl border border-white/20 rounded-[30px] 
                         bg-gradient-to-br from-gray-900/80 via-black/90 to-green-900/80 
                         shadow-2xl shadow-black/90 p-8">

                {{-- شعار وتفاصيل النظام --}}
                <div class="text-center mb-6">
                    <div class="mx-auto w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl 
                                 flex items-center justify-center shadow-lg ring-2 ring-gray-400/80 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <h2 class="text-3xl font-extrabold text-white tracking-widest"
                        style="-webkit-text-stroke: 1px rgba(255, 255, 255, 0.4); text-stroke: 1px rgba(255, 255, 255, 0.4);">
                        UniSmart
                    </h2>
                </div>
                
                <div class="text-center mb-6">
                    <h4 class="text-2xl font-bold text-white mb-1">
                        {{ __('Forgot Password?') }}
                    </h4>
                    <p class="text-sm text-white/70">
                        {{ __('Enter your email to receive the reset link.') }}
                    </p>
                </div>
                
                <hr class="border-t border-white/10 mb-6"/>

                {{-- نص الرسالة بنفس ستايل الخط الأبيض الشفاف --}}
                <div class="mb-6 text-sm text-white/80 text-center leading-relaxed">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                {{-- رسالة الحالة (نجاح الإرسال) --}}
                @session('status')
                    <div class="mb-5 text-center text-green-400 font-medium">
                        {{ $value }}
                    </div>
                @endsession

                <x-validation-errors class="mb-5 text-red-400" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-8">
                        {{-- <x-label for="email" value="{{ __('Email') }}" class="text-white/70 text-base mb-1" /> --}}
                        
                        {{-- حقل الإيميل بنفس الستايل الغامق --}}
                        <x-input id="email"
                                 class="block w-full px-6 py-4 rounded-xl 
                                        bg-green-900/40 border-transparent text-white placeholder-white/50
                                        focus:outline-none focus:ring-2 focus:ring-green-400/40 focus:border-green-300
                                        text-lg font-semibold shadow-inner" 
                                 type="email" name="email" :value="old('email')" required autofocus
                                 autocomplete="username" placeholder="Email Address" />
                    </div>

                    {{-- زر الإرسال بنفس الستايل الأخضر المتدرج --}}
                    <button type="submit"
                            class="w-full py-4 rounded-xl text-white font-bold text-xl
                                   bg-gradient-to-r from-green-600 to-green-800
                                   hover:from-green-500 hover:to-green-700
                                   shadow-lg transform transition hover:scale-[1.01] duration-300">
                        {{ __('Email Password Reset Link') }}
                    </button>
                    
                    {{-- زر العودة إلى تسجيل الدخول --}}
                    <p class="mt-4 text-center text-white/70 text-sm">
                        <a href="{{ route('login') }}" class="font-bold text-green-400 hover:text-green-300 underline decoration-green-400">
                            {{ __('Back to Sign In') }}
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>