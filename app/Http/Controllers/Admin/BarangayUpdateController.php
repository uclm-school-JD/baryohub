<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangayUpdate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarangayUpdateController extends Controller
{
    public function index(): View
    {
        return view('admin.barangay-updates.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        BarangayUpdate::create([
            ...$validated,
            'published_at' => now(),
        ]);

        return to_route('admin.barangay-updates.index')
            ->with('status', 'Barangay update published.');
    }
}
