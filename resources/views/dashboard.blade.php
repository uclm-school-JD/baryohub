<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold text-slate-900">
                Dashboard
            </h1>

            @if (auth()->user()->hasVerifiedEmail())
                <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700">
                    <span aria-hidden="true" class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Email verified
                </span>
            @endif
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-blue-700 to-sky-600 p-7 text-white shadow-xl shadow-blue-900/10 sm:p-10">
                <div class="absolute -right-10 -top-16 h-64 w-64 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-24 right-32 h-56 w-56 rounded-full bg-cyan-300/10"></div>

                <div class="relative max-w-2xl">
                    <h2 class="break-words text-3xl font-extrabold tracking-tight sm:text-4xl">
                        Welcome, {{ auth()->user()->name }}!
                    </h2>

                    <p class="mt-3 max-w-xl text-sm leading-6 text-blue-100 sm:text-base">
                        Manage your name, email address, and password.
                    </p>

                    <a href="{{ route('profile.edit') }}"
                       class="mt-7 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-indigo-700 shadow-lg transition hover:-translate-y-0.5 hover:bg-blue-50">
                        Review profile
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </section>

            <section aria-labelledby="resident-services-heading" class="space-y-4">
                <h2 id="resident-services-heading" class="text-xl font-bold text-slate-900">Resident services</h2>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-slate-600">Pending requests</p>
                        <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $pendingRequests }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-slate-600">Ready for pickup</p>
                        <p class="mt-2 text-3xl font-extrabold text-emerald-700">{{ $readyRequests }}</p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <a href="{{ route('requests.index') }}#new-request"
                       class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md">
                        <h3 class="font-bold text-slate-900">Request a document</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Apply for a barangay clearance or certificate.</p>
                    </a>
                    <a href="{{ route('requests.index') }}#request-history"
                       class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md">
                        <h3 class="font-bold text-slate-900">Track a request</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">See the status and history of your requests.</p>
                    </a>
                    <a href="{{ route('barangay-updates.index') }}"
                       class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md">
                        <h3 class="font-bold text-slate-900">Barangay updates</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Read public announcements from your barangay.</p>
                    </a>
                </div>
            </section>

            @canany(['process-document-requests', 'manage-staff-access', 'publish-barangay-updates'])
                <section aria-labelledby="office-tools-heading" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 id="office-tools-heading" class="text-xl font-bold text-slate-900">Office tools</h2>
                    <ul class="mt-4 flex flex-wrap gap-x-6 gap-y-3 text-sm font-semibold text-indigo-700">
                        @can('process-document-requests')
                            <li><a class="underline hover:text-indigo-900" href="{{ route('office.requests.index') }}">Office request queue</a></li>
                        @endcan
                        @can('manage-staff-access')
                            <li><a class="underline hover:text-indigo-900" href="{{ route('admin.staff.index') }}">Manage staff access</a></li>
                        @endcan
                        @can('publish-barangay-updates')
                            <li><a class="underline hover:text-indigo-900" href="{{ route('admin.barangay-updates.index') }}">Publish barangay updates</a></li>
                        @endcan
                    </ul>
                </section>
            @endcanany
        </div>
    </div>
</x-app-layout>
