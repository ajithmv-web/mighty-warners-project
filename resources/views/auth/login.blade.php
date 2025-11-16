<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative w-full">
                <!-- Password Input -->
                <input id="password"
                    type="password"
                    name="password"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 
                           focus:ring-indigo-500 rounded-md pr-12"
                    required autocomplete="current-password">

                <!-- Eye Icon (Inside Textbox) -->
                <button type="button"
                    onclick="togglePassword()"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-500">

                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 
                               9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Bottom Row -->
        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center gap-4">
                @if (Route::has('register'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900
                        rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 
                        focus:ring-indigo-500" href="{{ route('register') }}">
                        {{ __('Don\'t have an account? Register') }}
                    </a>
                @endif
            </div>

            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Password Show/Hide Script -->
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');

            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943
                           -9.542-7a9.965 9.965 0 012.113-3.362m3.172-2.291A9.953 
                           9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.965 
                           9.965 0 01-2.113 3.362M15 12a3 3 0 11-6 0 
                           3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3l18 18" />
                `;
            } else {
                input.type = "password";
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 
                           2.943 9.542 7-1.274 4.057-5.065 7-9.542 7
                           -4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</x-guest-layout>
