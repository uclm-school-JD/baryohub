<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CapstoneModulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_can_save_personal_data_and_access_own_card(): void
    {
        $resident=User::factory()->create();
        $this->actingAs($resident)->post(route('resident-services.profile'),[
            'birth_date'=>'1995-01-01','sex'=>'Male','civil_status'=>'Single',
            'barangay'=>'Lahug','address'=>'Example address','contact_no'=>'09123456789',
        ])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseHas('resident_profiles',['user_id'=>$resident->id,'barangay'=>'Lahug']);
        $this->get(route('resident-services.card'))->assertOk()->assertSee($resident->name);
    }

    public function test_resident_cannot_access_admin_or_export(): void
    {
        $this->actingAs(User::factory()->create())->get(route('capstone.admin.index'))->assertForbidden();
        $this->get(route('capstone.admin.export'))->assertForbidden();
    }

    public function test_admin_can_approve_resident_and_publish_notification(): void
    {
        $admin=User::factory()->create(['role'=>User::ROLE_ADMIN]);
        $resident=User::factory()->create();
        DB::table('users')->where('id',$resident->id)->update(['approval_status'=>'pending']);
        $this->actingAs($admin)->patch(route('capstone.admin.approval',$resident),['approval_status'=>'approved'])->assertRedirect();
        $this->assertDatabaseHas('users',['id'=>$resident->id,'approval_status'=>'approved']);
        $this->assertDatabaseHas('service_notifications',['user_id'=>$resident->id,'message'=>'Account status: approved']);
        $this->get(route('capstone.admin.index'))->assertOk();
        $this->get(route('capstone.admin.export'))->assertOk()->assertDownload('baryohub-document-report.csv');
    }

    public function test_pending_account_cannot_submit_incident(): void
    {
        $resident=User::factory()->create();
        DB::table('users')->where('id',$resident->id)->update(['approval_status'=>'pending']);
        $this->actingAs($resident->fresh())->post(route('resident-services.incidents'),[
            'subject'=>'Test','description'=>'Test report','incident_date'=>'2026-01-01',
        ])->assertForbidden();
    }

    public function test_resident_can_submit_incident_and_admin_can_resolve_it(): void
    {
        $resident=User::factory()->create();
        $admin=User::factory()->create(['role'=>User::ROLE_ADMIN]);
        $this->actingAs($resident)->post(route('resident-services.incidents'),[
            'subject'=>'Test','description'=>'Test report','incident_date'=>'2026-01-01',
        ])->assertRedirect()->assertSessionHasNoErrors();
        $incident=DB::table('incident_reports')->where('user_id',$resident->id)->first();
        $this->actingAs($admin)->patch(route('capstone.admin.incident',$incident->id),['status'=>'resolved'])->assertRedirect();
        $this->assertDatabaseHas('incident_reports',['id'=>$incident->id,'status'=>'resolved']);
    }
}
