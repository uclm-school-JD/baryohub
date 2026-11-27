<?php

namespace App\Http\Controllers\Office;

use App\Enums\DocumentRequestStatus;
use App\Http\Controllers\Controller;
use App\Models\DocumentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DocumentRequestController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'reference' => ['nullable', 'string', 'max:30'],
            'status' => ['nullable', Rule::enum(DocumentRequestStatus::class)],
        ]);
        $reference = Str::upper($filters['reference'] ?? '');
        $status = $filters['status'] ?? null;

        $documentRequests = DocumentRequest::query()
            ->with('user:id,name,email')
            ->when($reference !== '', fn ($query) => $query->where('reference', $reference))
            ->when($status !== null, fn ($query) => $query->where('status', $status))
            ->latest('id')
            ->paginate(15)
            ->appends($filters);

        return view('office.requests.index', [
            'documentRequests' => $documentRequests,
            'statuses' => DocumentRequestStatus::cases(),
            'reference' => $reference,
            'status' => $status,
        ]);
    }

    public function update(Request $request, DocumentRequest $documentRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(DocumentRequestStatus::class)],
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($documentRequest, $validated) {
        $changed = $documentRequest->newQuery()
            ->whereKey($documentRequest->getKey())
            ->where('status', '!=', $validated['status'])
            ->update(['status' => $validated['status']]);
        if ($changed) { \Illuminate\Support\Facades\DB::table('service_notifications')->insert(['user_id'=>$documentRequest->user_id,'message'=>'Document '.$documentRequest->reference.': '.$validated['status'],'created_at'=>now(),'updated_at'=>now()]); }
        });

        return back()->with('status', "Request {$documentRequest->reference} was updated.");
    }
}
