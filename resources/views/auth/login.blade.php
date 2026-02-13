<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat relative"
        style="background-image: url('{{ asset('images/bg-login.jpg.jpeg') }}');">

        <div class="absolute inset-0 bg-black/40"></div>

        <div class="relative z-10 w-full max-w-lg mx-4">

            <div class="backdrop-blur-3xl border border-white/20 rounded-[30px] 
                        bg-gradient-to-br from-gray-900/80 via-black/90 to-green-900/80 
                        shadow-2xl shadow-black/90 p-8">

                <!-- Logo -->
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
                        style="-webkit-text-stroke: 1px rgba(255, 255, 255, 0.4);">
                        UniSmart
                    </h2>

                    <p class="mt-1 text-white/70 text-sm">
                        College Facility Management System
                    </p>
                </div>

                <!-- Sign in -->
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-white mb-1">
                        {{ __('Sign In') }}
                    </h2>
                    <p class="text-sm text-white/70">
                        {{ __('Access your college management account') }}
                    </p>
                </div>

                <x-validation-errors class="mb-5 text-red-400" />

                @session('status')
                    <div class="mb-5 text-center text-green-400 font-medium">
                        {{ $value }}
                    </div>
                @endsession

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email – رمادي غامق --}}
                    <div>
                        <x-input id="email"
                            class="block w-full px-5 py-3 rounded-xl 
                                   bg-gray-800/60 border border-white/10 text-white placeholder-white/50
                                   focus:outline-none focus:ring-2 focus:ring-green-400/40 focus:border-green-300
                                   text-base font-semibold shadow-inner transition-colors duration-300
                                   caret-white"
                            type="email" name="email" :value="old('email')" required autofocus
                            autocomplete="username" placeholder="Email Address" />
                    </div>

                    {{-- Password – رمادي غامق --}}
                    <div class="pb-2">
                        <x-input id="password"
                            class="block w-full px-5 py-3 rounded-xl 
                                   bg-gray-800/60 border border-white/10 text-white placeholder-white/50
                                   focus:outline-none focus:ring-2 focus:ring-green-400/40 focus:border-green-300
                                   text-base font-semibold shadow-inner transition-colors duration-300
                                   caret-white"
                            type="password" name="password" required autocomplete="current-password"
                            placeholder="Password" />
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="rounded text-green-600 focus:ring-green-500 bg-black/20 border-white/30" />
                            <label for="remember_me" class="ml-2 text-sm text-white/70">
                                {{ __('Remember me') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="text-sm font-bold text-green-400 hover:text-green-300 underline decoration-green-300"
                                href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit"
                        class="w-full mt-6 py-3 rounded-xl text-white font-bold text-lg
                               bg-gradient-to-r from-green-600 to-green-800
                               hover:from-green-500 hover:to-green-700
                               shadow-lg transform transition hover:scale-[1.01] duration-300">
                        Sign In
                    </button>
                </form>

                <!-- Register -->
                <p class="mt-4 text-center text-white/70 text-sm">
                    Don't have an account?
                    <a href="{{ route('register') }}"
                        class="font-bold text-green-400 hover:text-green-300 underline decoration-green-400">
                        Register Here
                    </a>
                </p>

            </div>
        </div>
    </div>
</x-guest-layout>