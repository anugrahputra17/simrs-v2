<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Registration;
use App\Models\MedicalRecord;
use App\Models\Coding;
use App\Models\HybridTracker;
use App\Models\AuditTrail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Core Users
        $users = [
            ['username' => 'admin', 'role' => 'admin'],
            ['username' => 'dr.budi', 'role' => 'doctor'],
            ['username' => 'coder.siti', 'role' => 'coder'],
            ['username' => 'dir.andi', 'role' => 'director'],
            ['username' => 'petugas.loket', 'role' => 'admin'],
        ];

        foreach ($users as $u) {
            User::create([
                'username' => $u['username'],
                'password' => Hash::make('password'),
                'role' => $u['role'],
            ]);
        }

        // 2. Create Sample Patients
        $patientsData = [
            ['nama' => 'Bapak Joko Santoso', 'nik' => '3201234567890001', 'dob' => '1975-04-12', 'jk' => 1, 'penjamin' => 'BPJS', 'klinik' => 'Poli Penyakit Dalam', 'status' => 'done'],
            ['nama' => 'Ibu Siti Aminah', 'nik' => '3201234567890002', 'dob' => '1982-08-25', 'jk' => 2, 'penjamin' => 'Asuransi Swasta', 'klinik' => 'Poli Umum', 'status' => 'treating'],
            ['nama' => 'Sdr. Agus Pratama', 'nik' => '3201234567890003', 'dob' => '1995-11-05', 'jk' => 1, 'penjamin' => 'Umum', 'klinik' => 'IGD', 'status' => 'waiting'],
            ['nama' => 'Ibu Ratna Sari', 'nik' => '3201234567890004', 'dob' => '1968-02-18', 'jk' => 2, 'penjamin' => 'BPJS', 'klinik' => 'Poli Anak', 'status' => 'done'],
            ['nama' => 'Sdr. Fajar Hidayat', 'nik' => '3201234567890005', 'dob' => '1990-01-10', 'jk' => 1, 'penjamin' => 'BPJS', 'klinik' => 'Poli Bedah', 'status' => 'done'],
            ['nama' => 'Bapak Budi Hartono', 'nik' => '3201234567890006', 'dob' => '1960-05-15', 'jk' => 1, 'penjamin' => 'Umum', 'klinik' => 'Poli Penyakit Dalam', 'status' => 'waiting'],
            ['nama' => 'Ibu Maria Ulfa', 'nik' => '3201234567890007', 'dob' => '1988-12-01', 'jk' => 2, 'penjamin' => 'BPJS', 'klinik' => 'Poli Umum', 'status' => 'done'],
            ['nama' => 'Anak Kevin', 'nik' => '3201234567890008', 'dob' => '2015-07-20', 'jk' => 1, 'penjamin' => 'BPJS', 'klinik' => 'Poli Anak', 'status' => 'treating'],
        ];

        foreach ($patientsData as $index => $data) {
            $nomorRm = 'RM-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT);
            
            $patient = Patient::create([
                'no_rm' => $nomorRm,
                'gelar_kehormatan' => null,
                'nama_lengkap' => strtoupper($data['nama']),
                'nik' => $data['nik'],
                'no_bpjs' => $data['penjamin'] === 'BPJS' ? '00012345678' . str_pad($index, 2, '0', STR_PAD_LEFT) : null,
                'no_identitas_lain' => null,
                'status_merokok' => ($index % 3 == 0) ? true : false,
                'nama_ibu_kandung' => 'Ibu Fulanah',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => $data['dob'],
                'jenis_kelamin' => $data['jk'],
                'agama' => 1,
                'agama_lainnya' => null,
                'suku' => 'Jawa',
                'bahasa_dikuasai' => 'Indonesia',
                'no_telepon_rumah' => null,
                'no_hp' => '081234567' . str_pad($index, 3, '0', STR_PAD_LEFT),
                'pendidikan' => 5,
                'pekerjaan' => 2,
                'pekerjaan_lainnya' => null,
                'status_pernikahan' => 2,
                
                'emergency_nama' => 'Keluarga Pasien',
                'emergency_hubungan' => 'Saudara',
                'emergency_no_ktp' => '3201234567891234',
                'emergency_no_hp' => '089988776655',
                'emergency_alamat' => 'Alamat Keluarga',

                'alamat_ktp' => 'Jl. Kebangsaan No. ' . ($index + 1),
                'rt_ktp' => '001',
                'rw_ktp' => '002',
                'kelurahan_id_ktp' => 101,
                'kecamatan_id_ktp' => 101,
                'kabupaten_id_ktp' => 101,
                'provinsi_id_ktp' => 32,
                'kode_pos_ktp' => '12345',
                'negara_ktp' => 'Indonesia',
                'alamat_domisili' => 'Jl. Kebangsaan No. ' . ($index + 1),
                'rt_domisili' => '001',
                'rw_domisili' => '002',
                'kelurahan_id_domisili' => 101,
                'kecamatan_id_domisili' => 101,
                'kabupaten_id_domisili' => 101,
                'provinsi_id_domisili' => 32,
                'kode_pos_domisili' => '12345',
                'negara_domisili' => 'Indonesia',
                'penjamin' => $data['penjamin'],
            ]);

            $tracker = HybridTracker::create([
                'patient_id' => $patient->id,
                'nomor_rak' => ($data['status'] === 'done') ? 'R-0' . ($index % 5 + 1) . '-A' : null,
                'status_scan' => ($data['status'] === 'done' && $index % 2 == 0) ? true : false,
                'is_lengkap' => ($data['status'] === 'done') ? true : false,
            ]);

            $reg = Registration::create([
                'patient_id' => $patient->id,
                'type_kunjungan' => 'Baru',
                'klinik_tujuan' => $data['klinik'],
                'status_antrean' => $data['status'],
                'created_at' => Carbon::now()->subHours(rand(1, 48)),
            ]);

            // Create Medical Records & Codings for DONE and TREATING patients
            if ($data['status'] === 'done' || $data['status'] === 'treating') {
                $mr = MedicalRecord::create([
                    'registration_id' => $reg->id,
                    'subjektif' => 'Pasien mengeluhkan demam dan batuk sejak 3 hari yang lalu. Badan terasa lemas.',
                    'objektif' => 'Tampak sakit sedang, compos mentis. Suhu tubuh ' . (37 + rand(0, 20)/10) . ' C. Terdapat ronki di paru kanan.',
                    'asesmen' => 'Suspect ISPA atau Pneumonia ringan.',
                    'plan' => 'Pemberian antibiotik, paracetamol, dan multivitamin. Observasi 3 hari. Cek lab darah rutin.',
                    'tensi' => '1' . rand(10, 30) . '/80',
                    'nadi' => rand(80, 110),
                    'suhu' => (37 + rand(0, 20)/10),
                ]);

                if ($data['status'] === 'done') {
                    // Coder has processed
                    Coding::create([
                        'medical_record_id' => $mr->id,
                        'snomed_concept_id' => '195967001',
                        'snomed_term' => 'Asthma',
                        'icd10_mapped_code' => 'J45.909',
                        'is_primary_diagnosis' => true,
                        'miscoding_status' => null,
                    ]);
                }
            }

            AuditTrail::log('CREATE', 'patients');
            AuditTrail::log('CREATE', 'registrations');
        }
    }
}
