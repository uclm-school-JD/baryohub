<?php

namespace App\Http\Controllers;

use App\Models\BarangayUpdate;
use Illuminate\View\View;

class BarangayUpdateController extends Controller
{
    public function index(): View
    {
        return view('barangay-updates.index', [
            'updates' => BarangayUpdate::query()
                ->where('published_at', '<=', now())
                ->latest('published_at')
                ->latest('id')
                ->paginate(10),
        ]);
    }
}
