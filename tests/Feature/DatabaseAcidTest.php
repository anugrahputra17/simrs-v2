<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Exception;
use Tests\TestCase;

class DatabaseAcidTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test ACID (Atomicity) pada raw DB::transaction.
     * Membuktikan bahwa jika terjadi error di tengah blok, semua perintah sebelumnya akan dibatalkan.
     */
    public function test_atomicity_rolls_back_on_failure()
    {
        $initialCount = Patient::count();

        try {
            DB::transaction(function () {
                // 1. Eksekusi query pertama yang BERHASIL
                Patient::create([
                    'no_rm' => 'RM-999999',
                    'nama_lengkap' => 'Pasien Uji Coba ACID',
                    'nik' => '1111222233334444',
                    'status_merokok' => false,
                    'nama_ibu_kandung' => 'Ibu Test',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '1990-01-01',
                    'jenis_kelamin' => 1,
                    'agama' => 1,
                    'suku' => 'Jawa',
                    'bahasa_dikuasai' => 'Indonesia',
                    'no_hp' => '08123456789',
                    'pendidikan' => 1,
                    'pekerjaan' => 1,
                    'status_pernikahan' => 1,
                    'emergency_nama' => 'Darurat',
                    'emergency_hubungan' => 'Saudara',
                    'emergency_no_ktp' => '1111222233334444',
                    'emergency_no_hp' => '08123',
                    'emergency_alamat' => 'Alamat',
                    'alamat_ktp' => 'Jalan',
                    'rt_ktp' => '001',
                    'rw_ktp' => '002',
                    'kelurahan_ktp' => 'Kel',
                    'kecamatan_ktp' => 'Kec',
                    'kabupaten_ktp' => 'Kab',
                    'provinsi_ktp' => 'Prov',
                    'kode_pos_ktp' => '12345',
                    'negara_ktp' => 'ID',
                    'alamat_domisili' => 'Jalan',
                    'rt_domisili' => '001',
                    'rw_domisili' => '002',
                    'kelurahan_domisili' => 'Kel',
                    'kecamatan_domisili' => 'Kec',
                    'kabupaten_domisili' => 'Kab',
                    'provinsi_domisili' => 'Prov',
                    'kode_pos_domisili' => '12345',
                    'negara_domisili' => 'ID',
                    'penjamin' => 'UMUM'
                ]);

                // Pastikan data benar-benar sempat masuk di dalam scope transaksi
                $this->assertEquals($initialCount + 1, Patient::count());

                // 2. Simulasi Error yang terjadi di tengah transaksi
                // (misal: sistem gagal saat insert tabel HybridTracker/Registrasi)
                throw new Exception("Sistem gagal terkoneksi atau error logic");

                // Kode di bawah ini tidak akan pernah dieksekusi
            });
        } catch (Exception $e) {
            // Transaksi dibatalkan (Rollback)
        }

        // 3. ASSERT: Buktikan bahwa data Pasien yang sukses di-insert di atas
        // ternyata ikut dihapus/dibatalkan (Rollback) karena error setelahnya.
        $this->assertEquals($initialCount, Patient::count());
        $this->assertDatabaseMissing('patients', [
            'nik' => '1111222233334444'
        ]);
    }
}
