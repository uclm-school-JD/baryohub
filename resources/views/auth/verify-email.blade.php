<x-guest-layout>
    @php($usesMailLog = config('mail.default') === 'log')

    <div class="mb-4 text-sm text-gray-600">
        @if ($usesMailLog)
            {{ __('Open the verification link in the application log to verify your email address.') }}
        @else
            {{ __('Check your inbox for a verification link and open it to verify your email address.') }}
        @endif
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ $usesMailLog ? __('A new verification link was written to the application log.') : __('A new verification link was sent to your email address.') }}
        </div>
    @endif

    <div class="mt-4 flex flex-wrap items-center gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ $usesMailLog ? __('Generate New Link') : __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <a href="{{ route('profile.edit') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ __('Change Email Address') }}
        </a>

        <form method="POST" action="{{ route('logout') }}" class="ms-auto">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
