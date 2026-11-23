<?php

namespace App\Http\Controllers;

use App\Enums\DocumentType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DocumentRequestController extends Controller
{
    public function index(Request $request): View
    {
        return view('requests.index', [
            'documentRequests' => $request->user()->documentRequests()->latest('id')->paginate(10),
            'documentTypes' => DocumentType::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', Rule::enum(DocumentType::class)],
            'purpose' => ['required', 'string', 'max:1000'],
        ]);

        $documentRequest = $request->user()->documentRequests()->create($validated);

        return to_route('requests.index')
            ->with('status', "Request {$documentRequest->reference} was submitted.");
    }
}
