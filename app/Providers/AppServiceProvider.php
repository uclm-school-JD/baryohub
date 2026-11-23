<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('process-document-requests', fn (User $user): bool => in_array($user->role, [User::ROLE_STAFF, User::ROLE_ADMIN], true));
        Gate::define('manage-staff-access', fn (User $user): bool => $user->role === User::ROLE_ADMIN);
        Gate::define('publish-barangay-updates', fn (User $user): bool => $user->role === User::ROLE_ADMIN);
    }
}
