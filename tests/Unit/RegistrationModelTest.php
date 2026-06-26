<?php

namespace Tests\Unit;

use App\Models\Registration;
use App\Models\Patient;
use App\Models\MedicalRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_belongs_to_patient()
    {
        $patient = Patient::factory()->create();
        $registration = Registration::create([
            'patient_id' => $patient->id,
            'type_kunjungan' => 'Baru',
            'klinik_tujuan' => 'Umum',
            'status_antrean' => 'waiting'
        ]);

        $this->assertInstanceOf(Patient::class, $registration->patient);
        $this->assertEquals($patient->id, $registration->patient->id);
    }
    
    public function test_registration_has_one_medical_record()
    {
        $patient = Patient::factory()->create();
        $registration = Registration::create([
            'patient_id' => $patient->id,
            'type_kunjungan' => 'Baru',
            'klinik_tujuan' => 'Umum',
            'status_antrean' => 'done'
        ]);
        
        $medicalRecord = MedicalRecord::create([
            'registration_id' => $registration->id,
            'subjektif' => 'Sakit perut'
        ]);

        $this->assertInstanceOf(MedicalRecord::class, $registration->medicalRecord);
        $this->assertEquals($medicalRecord->id, $registration->medicalRecord->id);
    }
}
