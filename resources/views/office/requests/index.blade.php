<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Document request queue</h1>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <p role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </p>
        @endif

        <form method="GET" action="{{ route('office.requests.index') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-end gap-4">
                <div class="min-w-0 flex-1 basis-64">
                    <x-input-label for="reference" value="Reference number" />
                    <x-text-input id="reference" name="reference" :value="$reference" maxlength="30" class="mt-1 block w-full text-sm" />
                </div>

                <div class="min-w-0 flex-1 basis-48">
                    <x-input-label for="status-filter" value="Status" />
                    <select id="status-filter" name="status" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $requestStatus)
                            <option value="{{ $requestStatus->value }}" @selected($status === $requestStatus->value)>
                                {{ $requestStatus->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <x-primary-button>Filter requests</x-primary-button>
                    @if ($reference || $status)
                        <a href="{{ route('office.requests.index') }}" class="rounded text-sm font-medium text-indigo-700 hover:text-indigo-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-indigo-600">
                            Clear filters
                        </a>
                    @endif
                </div>
            </div>

            <x-input-error :messages="$errors->get('reference')" class="mt-3" />
            <x-input-error :messages="$errors->get('status')" class="mt-3" />
        </form>

        <section aria-label="Requests in the queue" class="space-y-3">
            @forelse ($documentRequests as $documentRequest)
                <article class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:grid-cols-5">
                    <div class="min-w-0 sm:col-span-3">
                        <h2 class="text-lg font-semibold text-slate-900">{{ $documentRequest->type->label() }}</h2>
                        <p class="mt-1 break-all font-mono text-xs text-slate-700">{{ $documentRequest->reference }}</p>
                        @if ($documentRequest->user)
                            <p class="mt-3 break-words text-sm font-medium text-slate-900">{{ $documentRequest->user->name }}</p>
                            <p class="break-words text-sm text-slate-600">{{ $documentRequest->user->email }}</p>
                        @endif
                        <p class="mt-3 whitespace-pre-wrap break-words text-sm text-slate-700">{{ $documentRequest->purpose }}</p>
                        <p class="mt-3 text-xs text-slate-600">
                            Submitted <time datetime="{{ $documentRequest->created_at->toDateString() }}">{{ $documentRequest->created_at->format('M j, Y') }}</time>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('office.requests.update', $documentRequest) }}" class="min-w-0 self-center sm:col-span-2">
                        @csrf
                        @method('PATCH')
                        <x-input-label :for="'status-'.$documentRequest->getKey()" value="Request status" />
                        <select id="status-{{ $documentRequest->getKey() }}" name="status" required class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ($statuses as $requestStatus)
                                <option value="{{ $requestStatus->value }}" @selected($documentRequest->status === $requestStatus)>
                                    {{ $requestStatus->label() }}
                                </option>
                            @endforeach
                        </select>
                        <x-primary-button class="mt-3">Save status</x-primary-button>
                    </form>
                </article>
            @empty
                <p class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-sm text-slate-600">
                    {{ $reference || $status ? 'No requests match these filters.' : 'There are no document requests yet.' }}
                </p>
            @endforelse
        </section>

        {{ $documentRequests->links() }}
    </div>
</x-app-layout>
