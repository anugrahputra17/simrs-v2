<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Registration;
use App\Models\MedicalRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TerminologyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_coding_list_page(): void
    {
        $user = User::factory()->create(['role' => 'coder']);
        
        $response = $this->actingAs($user)->get('/coding');
        $response->assertStatus(200);
    }

    public function test_can_store_coding_to_medical_record(): void
    {
        $user = User::factory()->create(['role' => 'coder']);
        $patient = Patient::factory()->create();
        $registration = Registration::create([
            'patient_id' => $patient->id,
            'type_kunjungan' => 'Baru',
            'klinik_tujuan' => 'Umum',
            'status_antrean' => 'done'
        ]);
        $medicalRecord = MedicalRecord::create([
            'registration_id' => $registration->id,
            'subjektif' => 'Sakit',
        ]);
        
        $response = $this->actingAs($user)->postJson('/api/coding/store', [
            'medical_record_id' => $medicalRecord->id,
            'snomed_concept_id' => '12345',
            'snomed_term' => 'Test Term',
            'icd10_mapped_code' => 'A00',
            'is_primary_diagnosis' => true
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('codings', [
            'medical_record_id' => $medicalRecord->id,
            'snomed_concept_id' => '12345'
        ]);
    }
}
