<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600">
                    BaryoHub Resident Portal
                </p>
                <h2 class="mt-1 text-2xl font-bold text-slate-900">
                    Dashboard
                </h2>
            </div>

            <span class="mt-2 inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700 sm:mt-0">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Account active
            </span>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">

            {{-- Welcome banner --}}
            <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-blue-700 to-sky-600 p-7 text-white shadow-xl shadow-blue-900/10 sm:p-10">
                <div class="absolute -right-10 -top-16 h-64 w-64 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-24 right-32 h-56 w-56 rounded-full bg-cyan-300/10"></div>

                <div class="relative max-w-2xl">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-50 ring-1 ring-white/20">
                        <span>✦</span>
                        Your community, connected
                    </span>

                    <h1 class="mt-5 text-3xl font-extrabold tracking-tight sm:text-4xl">
                        Welcome, {{ auth()->user()->name }}!
                    </h1>

                    <p class="mt-3 max-w-xl text-sm leading-6 text-blue-100 sm:text-base">
                        Access barangay services, request documents, and keep track
                        of your requests—all in one place.
                    </p>

                    <a href="#quick-actions"
                       class="mt-7 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-indigo-700 shadow-lg transition hover:-translate-y-0.5 hover:bg-blue-50">
                        Explore services
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </section>

            {{-- Summary cards --}}
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Pending requests</p>
                        <span class="grid h-10 w-10 place-items-center rounded-xl bg-amber-50 text-lg">⌛</span>
                    </div>
                    <p class="mt-4 text-3xl font-extrabold text-slate-900">0</p>
                    <p class="mt-1 text-xs text-slate-500">Awaiting barangay review</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Ready for pickup</p>
                        <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-50 text-lg">✓</span>
                    </div>
                    <p class="mt-4 text-3xl font-extrabold text-slate-900">0</p>
                    <p class="mt-1 text-xs text-slate-500">Approved documents</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Unread updates</p>
                        <span class="grid h-10 w-10 place-items-center rounded-xl bg-indigo-50 text-lg">✉</span>
                    </div>
                    <p class="mt-4 text-3xl font-extrabold text-slate-900">0</p>
                    <p class="mt-1 text-xs text-slate-500">Notifications from your barangay</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Resident status</p>
                        <span class="grid h-10 w-10 place-items-center rounded-xl bg-sky-50 text-lg">♙</span>
                    </div>
                    <p class="mt-4 text-xl font-extrabold text-emerald-700">Verified</p>
                    <p class="mt-1 text-xs text-slate-500">Your account is active</p>
                </div>
            </section>

            {{-- Quick actions --}}
            <section id="quick-actions">
                <div class="mb-4">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-600">
                        Resident services
                    </p>
                    <h2 class="mt-1 text-xl font-bold text-slate-900">Quick actions</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Start a service or check your latest activity.
                    </p>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <a href="#"
                       class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-lg">
                        <span class="grid h-12 w-12 place-items-center rounded-2xl bg-indigo-50 text-2xl transition group-hover:bg-indigo-100">📄</span>
                        <h3 class="mt-5 font-bold text-slate-900">Request a document</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Apply for a barangay clearance, certificate, or other document.
                        </p>
                        <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-indigo-700">
                            Start request <span>→</span>
                        </span>
                    </a>

                    <a href="#"
                       class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-sky-200 hover:shadow-lg">
                        <span class="grid h-12 w-12 place-items-center rounded-2xl bg-sky-50 text-2xl transition group-hover:bg-sky-100">🔎</span>
                        <h3 class="mt-5 font-bold text-slate-900">Track a request</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Check the status of a document request you have submitted.
                        </p>
                        <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-sky-700">
                            View status <span>→</span>
                        </span>
                    </a>

                    <a href="#"
                       class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-violet-200 hover:shadow-lg">
                        <span class="grid h-12 w-12 place-items-center rounded-2xl bg-violet-50 text-2xl transition group-hover:bg-violet-100">📢</span>
                        <h3 class="mt-5 font-bold text-slate-900">Barangay updates</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Read announcements and important community notices.
                        </p>
                        <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-violet-700">
                            See updates <span>→</span>
                        </span>
                    </a>
                </div>
            </section>

            {{-- Activity and notice --}}
            <section class="grid gap-5 lg:grid-cols-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-600">
                                Your account
                            </p>
                            <h2 class="mt-1 text-lg font-bold text-slate-900">Recent activity</h2>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                            Latest
                        </span>
                    </div>

                    <div class="mt-6 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center">
                        <span class="text-3xl">🗂️</span>
                        <p class="mt-3 font-semibold text-slate-800">Nothing to show yet</p>
                        <p class="mt-1 text-sm text-slate-500">
                            Your document requests and updates will appear here.
                        </p>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-2xl bg-slate-900 p-6 text-white shadow-sm lg:col-span-2">
                    <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-indigo-500/20"></div>
                    <div class="relative">
                        <span class="text-2xl">💡</span>
                        <p class="mt-4 text-xs font-bold uppercase tracking-[0.18em] text-sky-300">
                            Helpful reminder
                        </p>
                        <h2 class="mt-2 text-lg font-bold">Keep your profile updated</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-300">
                            Current contact details help barangay staff reach you about
                            your requests.
                        </p>
                        <a href="#"
                           class="mt-5 inline-flex rounded-lg bg-white/10 px-4 py-2.5 text-sm font-semibold text-white ring-1 ring-white/15 transition hover:bg-white/15">
                            Review profile
                        </a>
                    </div>
                </div>
            </section>

            <footer class="border-t border-slate-200 py-5 text-center text-xs text-slate-500">
                BaryoHub · Connecting residents with barangay services
            </footer>
        </div>
    </div>
</x-app-layout>