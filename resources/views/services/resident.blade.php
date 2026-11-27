<x-app-layout>
<x-slot name="header"><h1 class="text-xl font-bold">Resident services</h1></x-slot>
<div class="max-w-4xl mx-auto p-6 space-y-6">
@if(session('status'))<p role="status">{{ session('status') }}</p>@endif
@foreach($errors->all() as $error)<p class="text-red-700">{{ $error }}</p>@endforeach
<section class="bg-white rounded-xl p-6 shadow">
<h2 class="font-bold text-lg">Personal data sheet</h2><p>Account: {{ auth()->user()->approval_status }}. City: Cebu City</p>
<form method="POST" action="{{ route('resident-services.profile') }}" class="grid gap-3 mt-4">@csrf
@foreach(['birth_date'=>'Birth date','sex'=>'Sex (Female, Male, Prefer not to say)','civil_status'=>'Civil status (Single, Married, Widowed, Separated, Other)','barangay'=>'Barangay','address'=>'Address','contact_no'=>'Contact number'] as $field=>$label)
<label>{{ $label }}<input class="block rounded border-gray-300 w-full" required name="{{ $field }}" type="{{ $field==='birth_date'?'date':'text' }}" value="{{ old($field,$profile?->$field) }}"></label>
@endforeach
<x-primary-button>Save personal data</x-primary-button></form>
@if($profile && auth()->user()->approval_status==='approved')<a class="underline" href="{{ route('resident-services.card') }}">View printable resident record card</a>@endif
</section>
<section class="bg-white rounded-xl p-6 shadow"><h2 class="font-bold">Incident reporting</h2><p>For non-emergency reports only.</p>
@if(auth()->user()->approval_status==='approved')
<form method="POST" action="{{ route('resident-services.incidents') }}" class="grid gap-3">@csrf
<label>Subject<input class="block w-full" name="subject" required maxlength="150"></label><label>Date<input class="block w-full" type="date" name="incident_date" required></label><label>Description<textarea class="block w-full" name="description" required maxlength="5000"></textarea></label><x-primary-button>Submit incident</x-primary-button></form>
@endif
@foreach($incidents as $incident)<p class="border-t py-2">#{{ $incident->id }} {{ $incident->subject }} — {{ $incident->status }}</p>@endforeach
{{ $incidents->links() }}</section>
<section class="bg-white rounded-xl p-6 shadow"><h2 class="font-bold">Notifications</h2>
@forelse($notices as $notice)<p class="py-2 border-b">{{ $notice->message }} @if(!$notice->read_at) (New) @endif</p>@empty<p>No notifications.</p>@endforelse
<form method="POST" action="{{ route('resident-services.read') }}">@csrf<x-primary-button>Mark all as read</x-primary-button></form></section>
</div></x-app-layout>