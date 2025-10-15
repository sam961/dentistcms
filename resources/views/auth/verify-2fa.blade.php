<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('For your security, please enter the 6-digit verification code sent to your email.') }}
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.2fa.verify') }}">
        @csrf

        <div class="mb-4">
            <div class="text-sm text-gray-600 mb-2">
                Verification code sent to: <strong>{{ $email }}</strong>
            </div>
        </div>

        <!-- Verification Code -->
        <div>
            <x-input-label for="code" :value="__('Verification Code')" />
            <x-text-input id="code" class="block mt-1 w-full text-center text-2xl letter-spacing-wide font-mono"
                            type="text"
                            name="code"
                            required
                            autofocus
                            maxlength="6"
                            placeholder="000000"
                            pattern="[0-9]{6}"
                            inputmode="numeric" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
            <p class="mt-2 text-xs text-gray-500">
                This code will expire in 15 minutes
            </p>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Cancel Login') }}
            </a>

            <x-primary-button>
                {{ __('Verify & Login') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Resend Code -->
    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('login.2fa.resend') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                {{ __('Didn\'t receive the code? Send again') }}
            </button>
        </form>
    </div>

    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Security Notice:</strong> We've added this extra security step to protect your account. Check your email for the verification code.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
