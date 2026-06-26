<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AuditTrail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditTrailTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_audit_trail_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($user)->get('/audit-trail');
        $response->assertStatus(200);
    }

    public function test_audit_trail_page_displays_logs(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        AuditTrail::create([
            'user_id' => $user->id,
            'action' => 'TEST_ACTION_LOG',
            'table_name' => 'test_table'
        ]);

        $response = $this->actingAs($user)->get('/audit-trail');
        $response->assertStatus(200);
        $response->assertSee('TEST_ACTION_LOG');
    }
}
