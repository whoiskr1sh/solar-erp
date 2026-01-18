<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadContactVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // Create users
        $this->superAdmin = User::factory()->create(['role' => 'SUPER ADMIN']);
        $this->user1 = User::factory()->create(['role' => 'USER']);
        $this->user2 = User::factory()->create(['role' => 'USER']);
        // Create lead
        $this->lead = Lead::factory()->create(['assigned_user_id' => null]);
    }

    public function test_normal_user_sees_eye_button_for_unassigned_lead()
    {
        $response = $this->actingAs($this->user1)->get(route('leads.index'));
        $response->assertSee('👁️');
        $response->assertDontSee($this->lead->email);
        $response->assertDontSee($this->lead->phone);
    }

    public function test_user_assigns_lead_and_sees_contact()
    {
        $response = $this->actingAs($this->user1)->post(route('leads.reveal-contact', $this->lead->id));
        $response->assertStatus(302);
        $this->lead->refresh();
        $this->assertEquals($this->user1->id, $this->lead->assigned_user_id);
        $response = $this->actingAs($this->user1)->get(route('leads.index'));
        $response->assertSee($this->lead->email);
        $response->assertSee($this->lead->phone);
    }

    public function test_other_user_cannot_see_contact()
    {
        $this->lead->assigned_user_id = $this->user1->id;
        $this->lead->save();
        $response = $this->actingAs($this->user2)->get(route('leads.index'));
        $response->assertSee('Contact restricted');
        $response->assertDontSee($this->lead->email);
        $response->assertDontSee($this->lead->phone);
    }

    public function test_super_admin_sees_all_contacts()
    {
        $this->lead->assigned_user_id = $this->user1->id;
        $this->lead->save();
        $response = $this->actingAs($this->superAdmin)->get(route('leads.index'));
        $response->assertSee($this->lead->email);
        $response->assertSee($this->lead->phone);
    }

    public function test_api_access_blocked_for_unauthorized_user()
    {
        $this->lead->assigned_user_id = $this->user1->id;
        $this->lead->save();
        $response = $this->actingAs($this->user2)->postJson(route('leads.reveal-contact', $this->lead->id));
        $response->assertStatus(403);
        $response->assertJson(['success' => false]);
    }
}
