<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admission_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($user)->get('/admission');
        $response->assertStatus(200);
    }

    public function test_can_search_existing_patient_by_nik(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $patient = Patient::factory()->create(['nik' => '1234567890123456']);
        
        $response = $this->actingAs($user)->get('/admission/search?q=' . $patient->nik);
        
        $response->assertStatus(200)
                 ->assertJson([
                     'found' => true,
                     'patient' => [
                         'nik' => '1234567890123456'
                     ]
                 ]);
    }

    public function test_can_store_new_patient_and_create_queue(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $patientData = [
            'nama_lengkap' => 'PASIEN TEST BARU',
            'nik' => '3201112223334445',
            'no_bpjs' => '',
            'status_merokok' => 0,
            'nama_ibu_kandung' => 'Ibu Test',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 1,
            'agama' => 1,
            'suku' => 'Jawa',
            'bahasa_dikuasai' => 'Indonesia',
            'no_hp' => '08123456789',
            'pendidikan' => 5,
            'pekerjaan' => 2,
            'status_pernikahan' => 1,
            
            'emergency_nama' => 'Kerabat Test',
            'emergency_hubungan' => 'Kakak',
            'emergency_no_ktp' => '3201112223334444',
            'emergency_no_hp' => '08123456788',
            'emergency_alamat' => 'Alamat Kerabat',

            'alamat_ktp' => 'Jl. KTP No 1',
            'rt_ktp' => '001',
            'rw_ktp' => '002',
            'kelurahan_ktp' => 'Kelurahan X',
            'kecamatan_ktp' => 'Kecamatan Y',
            'kabupaten_ktp' => 'Kabupaten Z',
            'provinsi_ktp' => 'Provinsi W',
            'kode_pos_ktp' => '12345',
            'negara_ktp' => 'Indonesia',

            'alamat_domisili' => 'Jl. Domisili No 2',
            'rt_domisili' => '001',
            'rw_domisili' => '002',
            'kelurahan_domisili' => 'Kelurahan X',
            'kecamatan_domisili' => 'Kecamatan Y',
            'kabupaten_domisili' => 'Kabupaten Z',
            'provinsi_domisili' => 'Provinsi W',
            'kode_pos_domisili' => '12345',
            'negara_domisili' => 'Indonesia',

            'penjamin' => 'Umum',
            'klinik_tujuan' => 'Poli Umum'
        ];

        $response = $this->actingAs($user)->post('/admission', $patientData);

        $response->assertRedirect('/admission');
        $this->assertDatabaseHas('patients', [
            'nik' => '3201112223334445',
            'provinsi_ktp' => 'Provinsi W' // verifying the string column works
        ]);
        $this->assertDatabaseHas('registrations', [
            'klinik_tujuan' => 'Poli Umum',
            'status_antrean' => 'waiting'
        ]);
    }
}
