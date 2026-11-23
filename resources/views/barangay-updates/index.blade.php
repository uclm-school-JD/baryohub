<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-bold text-slate-900">Barangay updates</h1>

            @can('publish-barangay-updates')
                <a href="{{ route('admin.barangay-updates.index') }}" class="rounded-lg bg-indigo-700 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-800">
                    Publish an update
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl space-y-4 px-4 py-8 sm:px-6 lg:px-8">
        @forelse ($updates as $update)
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="break-words text-lg font-semibold text-slate-900">{{ $update->title }}</h2>
                <p class="mt-1 text-sm text-slate-600">
                    <time datetime="{{ $update->published_at->toIso8601String() }}">
                        {{ $update->published_at->format('M j, Y, g:i A T') }}
                    </time>
                </p>
                <p class="mt-4 whitespace-pre-wrap break-words text-slate-700">{{ $update->body }}</p>
            </article>
        @empty
            <p class="rounded-2xl border border-slate-200 bg-white p-6 text-slate-600">
                No barangay updates have been published yet.
            </p>
        @endforelse

        {{ $updates->links() }}
    </div>
</x-app-layout>
