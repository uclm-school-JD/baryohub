<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Manage staff access</h1>
    </x-slot>

    <div class="mx-auto max-w-4xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <p role="status" class="rounded-lg bg-emerald-50 p-4 text-sm text-emerald-800">
                {{ session('status') }}
            </p>
        @endif

        @error('role')
            <p role="alert" class="rounded-lg bg-red-50 p-4 text-sm text-red-800">
                {{ $message }}
            </p>
        @enderror

        <section aria-labelledby="accounts-heading" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 id="accounts-heading" class="text-lg font-bold text-slate-900">Resident and staff accounts</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Staff can open the office queue and update document request statuses.</p>

            <form method="GET" action="{{ route('admin.staff.index') }}" role="search" class="mt-5">
                <x-input-label for="q" value="Search by name or email" />
                <div class="mt-2 flex flex-wrap items-center gap-3 sm:flex-nowrap">
                    <x-text-input id="q" name="q" type="search" :value="$search" class="w-full" />
                    <x-primary-button>Search</x-primary-button>
                    @if ($search !== '')
                        <a href="{{ route('admin.staff.index') }}" class="shrink-0 text-sm font-semibold text-indigo-700 underline">Clear search</a>
                    @endif
                </div>
            </form>

            <ul class="mt-5 divide-y divide-slate-200">
                @forelse ($users as $user)
                    <li class="flex flex-col gap-3 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0 break-words">
                            <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                            <p class="text-sm text-slate-600">{{ $user->email }}</p>
                            <p class="mt-1 text-sm font-medium text-slate-700">{{ ucfirst($user->role) }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.staff.update', $user) }}">
                            @csrf
                            @method('PATCH')

                            @if ($user->role === \App\Models\User::ROLE_STAFF)
                                <input type="hidden" name="role" value="{{ \App\Models\User::ROLE_RESIDENT }}">
                                <x-secondary-button type="submit" aria-label="Revoke staff access for {{ $user->name }}">Revoke staff access</x-secondary-button>
                            @else
                                <input type="hidden" name="role" value="{{ \App\Models\User::ROLE_STAFF }}">
                                <x-secondary-button type="submit" aria-label="Grant staff access to {{ $user->name }}">Grant staff access</x-secondary-button>
                            @endif
                        </form>
                    </li>
                @empty
                    <li class="py-4 text-sm text-slate-600">No resident or staff accounts found.</li>
                @endforelse
            </ul>

            {{ $users->links() }}
        </section>
    </div>
</x-app-layout>
