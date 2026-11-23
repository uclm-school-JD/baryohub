<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_for_accounts_and_change_staff_access(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $resident = User::factory()->create(['name' => 'Ana Resident']);
        $otherAccount = User::factory()->create(['name' => 'Ben Resident']);

        $this->actingAs($admin)
            ->get(route('admin.staff.index', ['q' => 'ana']))
            ->assertOk()
            ->assertSee($resident->email)
            ->assertDontSee($otherAccount->email);

        $this->actingAs($admin)
            ->from(route('admin.staff.index'))
            ->patch(route('admin.staff.update', $resident), ['role' => User::ROLE_STAFF])
            ->assertRedirect(route('admin.staff.index'));

        $this->assertSame(User::ROLE_STAFF, $resident->fresh()->role);
        $this->actingAs($resident->fresh())->get(route('office.requests.index'))->assertOk();

        $this->actingAs($admin)
            ->from(route('admin.staff.index'))
            ->patch(route('admin.staff.update', $resident), ['role' => User::ROLE_RESIDENT])
            ->assertRedirect(route('admin.staff.index'));

        $this->assertSame(User::ROLE_RESIDENT, $resident->fresh()->role);
        $this->actingAs($resident->fresh())->get(route('office.requests.index'))->assertForbidden();
    }

    public function test_staff_cannot_manage_staff_access(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_STAFF]);

        $this->actingAs($staff)->get(route('admin.staff.index'))->assertForbidden();
    }

    public function test_console_command_grants_admin_access_to_an_existing_account(): void
    {
        $user = User::factory()->create();

        $this->artisan('baryohub:grant-admin', ['email' => strtoupper($user->email)])
            ->expectsOutput('Administrator access granted.')
            ->assertSuccessful();

        $this->assertSame(User::ROLE_ADMIN, $user->fresh()->role);
    }
}
