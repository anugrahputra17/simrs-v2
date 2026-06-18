<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Registration;
use App\Models\MedicalRecord;
use App\Models\HybridTracker;
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
            ['nama' => 'Bapak Joko', 'nik' => '3201234567890001', 'dob' => '1975-04-12', 'jk' => 'L', 'penjamin' => 'BPJS', 'klinik' => 'Poli Penyakit Dalam'],
            ['nama' => 'Ibu Siti', 'nik' => '3201234567890002', 'dob' => '1982-08-25', 'jk' => 'P', 'penjamin' => 'Asuransi Swasta', 'klinik' => 'Poli Umum'],
            ['nama' => 'Sdr. Agus', 'nik' => '3201234567890003', 'dob' => '1995-11-05', 'jk' => 'L', 'penjamin' => 'Umum', 'klinik' => 'IGD'],
            ['nama' => 'Ibu Ratna', 'nik' => '3201234567890004', 'dob' => '1968-02-18', 'jk' => 'P', 'penjamin' => 'BPJS', 'klinik' => 'Poli Penyakit Dalam'],
        ];

        foreach ($patientsData as $index => $data) {
            $nomorRm = 'RM-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT);
            
            $patient = Patient::create([
                'nomor_rm' => $nomorRm,
                'nama' => $data['nama'],
                'nik' => $data['nik'],
                'tanggal_lahir' => $data['dob'],
                'jenis_kelamin' => $data['jk'],
                'penjamin' => $data['penjamin'],
            ]);

            HybridTracker::create([
                'patient_id' => $patient->id,
                'nomor_rak' => null,
                'status_scan' => false,
                'is_lengkap' => false,
            ]);

            Registration::create([
                'patient_id' => $patient->id,
                'klinik_tujuan' => $data['klinik'],
                'status_antrean' => 'waiting',
            ]);
        }

        // Add one completed record for coding/dashboard testing
        $patientCompleted = Patient::create([
            'nomor_rm' => 'RM-000005',
            'nama' => 'Sdr. Fajar',
            'nik' => '3201234567890005',
            'tanggal_lahir' => '1990-01-10',
            'jenis_kelamin' => 'L',
            'penjamin' => 'BPJS',
        ]);

        HybridTracker::create([
            'patient_id' => $patientCompleted->id,
            'nomor_rak' => 'R-01-A',
            'status_scan' => true,
            'is_lengkap' => true,
        ]);

        $regCompleted = Registration::create([
            'patient_id' => $patientCompleted->id,
            'klinik_tujuan' => 'Poli Bedah',
            'status_antrean' => 'done',
        ]);

        $mr = MedicalRecord::create([
            'registration_id' => $regCompleted->id,
            'subjektif' => 'Nyeri perut kanan bawah hebat sejak semalam',
            'objektif' => 'Nyeri tekan McBurney (+), defans muskuler (+)',
            'asesmen' => 'Suspect Appendicitis Acute',
            'plan' => 'Konsul bedah cito, pro appendectomy',
            'tensi' => '110/70',
            'nadi' => '100',
            'suhu' => '38.5',
        ]);

        // We leave the coding part to be tested via the UI!
    }
}
