<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('baryohub:grant-admin {email}', function (): int {
    $user = User::query()->where('email', Str::lower(trim($this->argument('email'))))->first();

    if ($user === null) {
        $this->error('No account matches that email address.');

        return 1;
    }

    $user->role = User::ROLE_ADMIN;
    $user->save();

    $this->info('Administrator access granted.');

    return 0;
})->purpose('Grant administrator access to an existing account');
