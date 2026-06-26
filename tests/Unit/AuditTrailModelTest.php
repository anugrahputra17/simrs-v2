<?php

namespace Tests\Unit;

use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class AuditTrailModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_trail_belongs_to_user()
    {
        $user = User::factory()->create();
        
        $audit = AuditTrail::create([
            'user_id' => $user->id,
            'action' => 'CREATE',
            'table_name' => 'patients',
        ]);

        $this->assertInstanceOf(User::class, $audit->user);
        $this->assertEquals($user->id, $audit->user->id);
    }

    public function test_log_static_method_creates_audit_trail()
    {
        $user = User::factory()->create();
        Auth::login($user);

        AuditTrail::log('UPDATE', 'medical_records');

        $this->assertDatabaseHas('audit_trails', [
            'user_id' => $user->id,
            'action' => 'UPDATE',
            'table_name' => 'medical_records'
        ]);
    }

    public function test_log_search_static_method_creates_audit_trail()
    {
        $user = User::factory()->create();
        Auth::login($user);

        AuditTrail::logSearch('nik=123', 'found');

        $this->assertDatabaseHas('audit_trails', [
            'user_id' => $user->id,
            'action' => 'SEARCH_FOUND',
            'table_name' => 'patients',
            'search_query_logged' => 'nik=123'
        ]);
    }
}
