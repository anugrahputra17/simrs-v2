<?php

namespace Tests\Unit;

use App\Models\MedicalRecord;
use App\Models\Registration;
use App\Models\Patient;
use App\Models\Coding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalRecordModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_medical_record_belongs_to_registration()
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
            'subjektif' => 'Sakit kepala',
            'tensi' => '120/80'
        ]);

        $this->assertInstanceOf(Registration::class, $medicalRecord->registration);
        $this->assertEquals($registration->id, $medicalRecord->registration->id);
    }

    public function test_medical_record_has_many_codings()
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
            'subjektif' => 'Sakit kepala'
        ]);

        Coding::create([
            'medical_record_id' => $medicalRecord->id,
            'snomed_concept_id' => '123456789',
            'snomed_term' => 'Headache',
            'is_primary_diagnosis' => true
        ]);

        Coding::create([
            'medical_record_id' => $medicalRecord->id,
            'snomed_concept_id' => '987654321',
            'snomed_term' => 'Fever',
            'is_primary_diagnosis' => false
        ]);

        $this->assertCount(2, $medicalRecord->codings);
    }
}
