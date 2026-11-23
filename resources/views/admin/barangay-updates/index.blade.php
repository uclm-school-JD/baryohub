<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-bold text-slate-900">Publish a barangay update</h1>
            <a href="{{ route('barangay-updates.index') }}" class="text-sm font-semibold text-indigo-700 underline hover:text-indigo-900">
                View published updates
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.barangay-updates.store') }}" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            @if (session('status'))
                <p role="status" class="rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                    {{ session('status') }}
                </p>
            @endif

            <div>
                <x-input-label for="title" value="Title" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" maxlength="150" required
                    :aria-invalid="$errors->has('title') ? 'true' : 'false'" :aria-describedby="$errors->has('title') ? 'update-title-error' : null" />
                <x-input-error id="update-title-error" :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="body" value="Message" />
                <textarea id="body" name="body" rows="8" maxlength="5000" required
                    @error('body') aria-invalid="true" aria-describedby="update-body-error" @enderror
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('body') }}</textarea>
                <x-input-error id="update-body-error" :messages="$errors->get('body')" class="mt-2" />
            </div>

            <x-primary-button>Publish update</x-primary-button>
        </form>
    </div>
</x-app-layout>
