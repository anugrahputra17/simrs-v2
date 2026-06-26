<?php

namespace Tests\Feature\Security;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class XssProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_data_is_escaped_on_clinical_page(): void
    {
        $user = User::factory()->create(['role' => 'doctor']);
        
        $maliciousScript = "<script>alert('xss')</script>";

        // Create a patient with malicious script in name
        $patient = Patient::factory()->create([
            'nama_lengkap' => $maliciousScript
        ]);

        \App\Models\Registration::create([
            'patient_id' => $patient->id,
            'type_kunjungan' => 'Baru',
            'klinik_tujuan' => 'Poli Umum',
            'status_antrean' => 'waiting'
        ]);

        // Actually the name is displayed on the clinical index
        $response = $this->actingAs($user)->get('/clinical');
        
        $response->assertStatus(200);

        // Assert the literal script tags are NOT present in raw form
        // Blade's {{ }} translates < to &lt; and > to &gt;
        $response->assertDontSee($maliciousScript, false);
        
        // Assert the escaped version IS present
        $escapedScript = e($maliciousScript);
        $response->assertSee($escapedScript, false);
    }
}
