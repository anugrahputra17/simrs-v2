<?php

namespace Tests\Unit;

use App\Models\MedicalRecord;
use App\Models\Registration;
use App\Models\Patient;
use App\Models\Coding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CodingModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_coding_belongs_to_medical_record()
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

        $coding = Coding::create([
            'medical_record_id' => $medicalRecord->id,
            'snomed_concept_id' => '123456789',
            'snomed_term' => 'Headache',
            'is_primary_diagnosis' => true
        ]);

        $this->assertInstanceOf(MedicalRecord::class, $coding->medicalRecord);
        $this->assertEquals($medicalRecord->id, $coding->medicalRecord->id);
    }

    public function test_coding_casts_boolean_properly()
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

        $coding = Coding::create([
            'medical_record_id' => $medicalRecord->id,
            'snomed_concept_id' => '123456789',
            'snomed_term' => 'Headache',
            'is_primary_diagnosis' => 1 // inserting as integer
        ]);

        $this->assertIsBool($coding->is_primary_diagnosis);
        $this->assertTrue($coding->is_primary_diagnosis);
    }
}
