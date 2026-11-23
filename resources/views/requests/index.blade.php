<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Document requests</h1>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <p role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </p>
        @endif

        <div class="grid items-start gap-6 lg:grid-cols-5">
            <section id="new-request" aria-labelledby="new-request-heading" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                <h2 id="new-request-heading" class="text-xl font-bold text-slate-900">Request a document</h2>

                <form method="POST" action="{{ route('requests.store') }}" class="mt-5 space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="type" value="Document type" />
                        <select id="type" name="type" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select a document</option>
                            @foreach ($documentTypes as $documentType)
                                <option value="{{ $documentType->value }}" @selected(old('type') === $documentType->value)>
                                    {{ $documentType->label() }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="purpose" value="Purpose" />
                        <textarea id="purpose" name="purpose" rows="4" maxlength="1000" required class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ is_string(old('purpose')) ? old('purpose') : '' }}</textarea>
                        <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                    </div>

                    <x-primary-button>Submit request</x-primary-button>
                </form>
            </section>

            <section id="request-history" aria-labelledby="request-history-heading" class="min-w-0 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-3">
                <h2 id="request-history-heading" class="text-xl font-bold text-slate-900">Track a request</h2>

                <div class="mt-5 space-y-3">
                    @forelse ($documentRequests as $documentRequest)
                        <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-2">
                                <h3 class="font-semibold text-slate-900">{{ $documentRequest->type->label() }}</h3>
                                <p @class([
                                    'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold',
                                    'bg-amber-100 text-amber-800' => $documentRequest->status->value === 'pending',
                                    'bg-sky-100 text-sky-800' => $documentRequest->status->value === 'in_progress',
                                    'bg-emerald-100 text-emerald-800' => $documentRequest->status->value === 'ready_for_pickup',
                                ])>{{ $documentRequest->status->label() }}</p>
                            </div>

                            <p class="mt-1 break-all font-mono text-xs text-slate-700">{{ $documentRequest->reference }}</p>
                            <p class="mt-3 whitespace-pre-wrap break-words text-sm text-slate-700">{{ $documentRequest->purpose }}</p>
                            <p class="mt-3 text-xs text-slate-600">
                                Submitted <time datetime="{{ $documentRequest->created_at->toDateString() }}">{{ $documentRequest->created_at->format('M j, Y') }}</time>
                            </p>
                        </article>
                    @empty
                        <p class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-5 text-sm text-slate-600">You have no document requests yet.</p>
                    @endforelse
                </div>

                @if ($documentRequests->hasPages())
                    <div class="mt-5">{{ $documentRequests->fragment('request-history')->links() }}</div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
