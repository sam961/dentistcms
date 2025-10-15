<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Please enter the 6-digit verification code sent to your email address.') }}
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('info'))
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded">
            {{ session('info') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.verify') }}">
        @csrf

        <!-- Email Address (hidden) -->
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-4">
            <div class="text-sm text-gray-600 mb-2">
                Verification email sent to: <strong>{{ $email }}</strong>
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
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Back to Login') }}
            </a>

            <x-primary-button>
                {{ __('Verify Email') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Resend Code -->
    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('verification.resend') }}" class="inline">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                {{ __('Didn\'t receive the code? Send again') }}
            </button>
        </form>
    </div>
</x-guest-layout>
