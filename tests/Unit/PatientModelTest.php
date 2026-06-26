<?php

namespace Tests\Unit;

use App\Models\Patient;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_patient_model(): void
    {
        $patient = Patient::factory()->create([
            'nama_lengkap' => 'TEST PATIENT',
            'provinsi_ktp' => 'DKI JAKARTA',
        ]);

        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'nama_lengkap' => 'TEST PATIENT',
            'provinsi_ktp' => 'DKI JAKARTA'
        ]);
    }

    public function test_patient_has_many_registrations(): void
    {
        $patient = Patient::factory()->create();
        
        $reg1 = Registration::create([
            'patient_id' => $patient->id,
            'type_kunjungan' => 'Baru',
            'klinik_tujuan' => 'Poli Umum',
            'status_antrean' => 'done',
        ]);

        $reg2 = Registration::create([
            'patient_id' => $patient->id,
            'type_kunjungan' => 'Lama',
            'klinik_tujuan' => 'Poli Bedah',
            'status_antrean' => 'waiting',
        ]);

        $this->assertCount(2, $patient->registrations);
        $this->assertTrue($patient->registrations->contains($reg1));
        $this->assertTrue($patient->registrations->contains($reg2));
    }
}
