<x-guest-layout>
    @php($usesMailLog = config('mail.default') === 'log')

    <div class="mb-4 text-sm text-gray-600">
        @if ($usesMailLog)
            {{ __('Enter the email address on your account to generate a password reset link in the application log.') }}
        @else
            {{ __('Enter the email address on your account and we will email you a password reset link.') }}
        @endif
    </div>

    <!-- Session Status -->
    @if ($usesMailLog && session('status'))
        <x-auth-session-status class="mb-4" :status="__('A password reset link was written to the application log.')" />
    @else
        <x-auth-session-status class="mb-4" :status="session('status')" />
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ $usesMailLog ? __('Generate Password Reset Link') : __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
