<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StaffAccessController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:255']]);
        $search = $filters['q'] ?? '';

        $users = User::query()
            ->whereIn('role', [User::ROLE_RESIDENT, User::ROLE_STAFF])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereLike('name', "%{$search}%")
                        ->orWhereLike('email', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.staff.index', compact('users', 'search'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'string', Rule::in([User::ROLE_RESIDENT, User::ROLE_STAFF])],
        ]);

        $updated = User::query()
            ->whereKey($user->getKey())
            ->whereIn('role', [User::ROLE_RESIDENT, User::ROLE_STAFF])
            ->update(['role' => $validated['role']]);

        abort_if($updated === 0 && $user->fresh()?->role !== $validated['role'], 404);

        return back()->with('status', $validated['role'] === User::ROLE_STAFF
            ? __('Staff access granted.')
            : __('Staff access revoked.'));
    }
}
