<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClinicalTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_access_clinical_workstation(): void
    {
        $user = User::factory()->create(['role' => 'doctor']);
        
        $response = $this->actingAs($user)->get('/clinical');
        $response->assertStatus(200);
    }

    public function test_doctor_can_save_medical_record(): void
    {
        $user = User::factory()->create(['role' => 'doctor']);
        $patient = Patient::factory()->create();
        $registration = Registration::create([
            'patient_id' => $patient->id,
            'type_kunjungan' => 'Baru',
            'klinik_tujuan' => 'Poli Umum',
            'status_antrean' => 'treating',
        ]);

        $medicalRecordData = [
            'registration_id' => $registration->id,
            'subjektif' => 'Pasien batuk',
            'objektif' => 'Suhu 38',
            'asesmen' => 'ISPA',
            'plan' => 'Beri obat batuk',
            'tensi' => '120/80',
            'nadi' => '80',
            'suhu' => '38',
        ];

        $response = $this->actingAs($user)->post('/clinical', $medicalRecordData);

        $response->assertRedirect('/clinical');
        $this->assertDatabaseHas('medical_records', [
            'registration_id' => $registration->id,
            'asesmen' => 'ISPA'
        ]);
        
        $this->assertDatabaseHas('registrations', [
            'id' => $registration->id,
            'status_antrean' => 'done'
        ]);
    }
}
