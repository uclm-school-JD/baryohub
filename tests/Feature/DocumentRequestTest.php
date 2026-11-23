<?php

namespace Tests\Feature;

use App\Enums\DocumentRequestStatus;
use App\Enums\DocumentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DocumentRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_residents_can_submit_and_track_their_own_document_requests(): void
    {
        $resident = User::factory()->create();
        $otherRequest = User::factory()->create()->documentRequests()->create([
            'type' => DocumentType::CertificateOfIndigency,
            'purpose' => 'Scholarship application',
        ]);

        $this->actingAs($resident)->post(route('requests.store'), [
            'type' => DocumentType::BarangayClearance->value,
            'purpose' => 'Employment requirements',
        ])->assertRedirect(route('requests.index'));

        $documentRequest = $resident->documentRequests()->sole();
        $this->assertStringStartsWith('BRH-', $documentRequest->reference);
        $this->assertSame(DocumentRequestStatus::Pending, $documentRequest->status);

        $this->get(route('requests.index'))
            ->assertOk()
            ->assertSeeText($documentRequest->reference)
            ->assertSeeText('Employment requirements')
            ->assertDontSeeText($otherRequest->reference);
    }

    public function test_staff_can_filter_the_queue_and_mark_a_request_ready_for_pickup(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_STAFF]);
        $resident = User::factory()->create();
        $documentRequest = $resident->documentRequests()->create([
            'type' => DocumentType::CertificateOfResidency,
            'purpose' => 'School enrollment',
        ]);
        $otherRequest = $resident->documentRequests()->create([
            'type' => DocumentType::BarangayClearance,
            'purpose' => 'Employment requirements',
        ]);

        $queue = route('office.requests.index', ['reference' => Str::lower($documentRequest->reference)]);

        $this->actingAs($staff)->get($queue)
            ->assertOk()
            ->assertSeeText($documentRequest->reference)
            ->assertDontSeeText($otherRequest->reference);

        $update = route('office.requests.update', $documentRequest);
        $ready = ['status' => DocumentRequestStatus::ReadyForPickup->value];

        $this->from($queue)->patch($update, $ready)->assertRedirect($queue);
        $this->assertSame(DocumentRequestStatus::ReadyForPickup, $documentRequest->refresh()->status);
        $updatedAt = $documentRequest->updated_at;

        $this->travel(1)->minute();
        $this->from($queue)->patch($update, $ready)->assertRedirect($queue);
        $this->assertTrue($updatedAt->equalTo($documentRequest->refresh()->updated_at));

        $this->get(route('office.requests.index', $ready))
            ->assertOk()
            ->assertSeeText($documentRequest->reference)
            ->assertDontSeeText($otherRequest->reference);

        $this->actingAs($resident)->get(route('requests.index'))
            ->assertOk()
            ->assertSeeText('Ready for pickup');
    }

    public function test_residents_do_not_have_access_to_the_office_queue(): void
    {
        $resident = User::factory()->create();
        $documentRequest = $resident->documentRequests()->create([
            'type' => DocumentType::BarangayClearance,
            'purpose' => 'Employment requirements',
        ]);

        $this->actingAs($resident)->get(route('office.requests.index'))->assertForbidden();

        $this->patch(route('office.requests.update', $documentRequest), [
            'status' => DocumentRequestStatus::ReadyForPickup->value,
        ])->assertForbidden();

        $this->assertSame(DocumentRequestStatus::Pending, $documentRequest->refresh()->status);
    }
}
