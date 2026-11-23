<?php

namespace Tests\Feature;

use App\Models\BarangayUpdate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BarangayUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_publish_an_update_for_residents(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $resident = User::factory()->create();
        $title = 'Barangay assembly';
        $body = 'The assembly starts at 9 AM at the barangay hall.';

        $this->actingAs($admin)
            ->get(route('admin.barangay-updates.index'))
            ->assertOk()
            ->assertSee('Publish a barangay update');

        $this->post(route('admin.barangay-updates.store'), ['title' => $title, 'body' => $body])
            ->assertRedirect(route('admin.barangay-updates.index'))
            ->assertSessionHasNoErrors();

        $this->actingAs($resident)
            ->get(route('barangay-updates.index'))
            ->assertOk()
            ->assertSee($title)
            ->assertSee($body);
    }

    public function test_residents_see_published_updates_newest_first(): void
    {
        $older = BarangayUpdate::create([
            'title' => 'Earlier announcement',
            'body' => 'Barangay hall hours.',
            'published_at' => now()->subDays(2),
        ]);
        $newer = BarangayUpdate::create([
            'title' => 'Latest announcement',
            'body' => 'Barangay hall closure.',
            'published_at' => now()->subDay(),
        ]);
        $future = BarangayUpdate::create([
            'title' => 'Future announcement',
            'body' => 'An upcoming notice.',
            'published_at' => now()->addDay(),
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('barangay-updates.index'))
            ->assertOk()
            ->assertSeeInOrder([$newer->title, $older->title])
            ->assertDontSee($future->title);
    }

    public function test_staff_do_not_have_access_to_the_publication_page(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_STAFF]);

        $this->actingAs($staff)
            ->get(route('admin.barangay-updates.index'))
            ->assertForbidden();

        $this->post(route('admin.barangay-updates.store'), ['title' => 'Notice', 'body' => 'An office update.'])
            ->assertForbidden();

        $this->assertDatabaseEmpty('barangay_updates');
    }
}
