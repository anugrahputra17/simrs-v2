<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'no_rm' => 'RM-' . $this->faker->unique()->numerify('######'),
            'gelar_kehormatan' => $this->faker->title(),
            'nama_lengkap' => strtoupper($this->faker->name()),
            'nik' => $this->faker->numerify('################'),
            'no_bpjs' => $this->faker->numerify('#############'),
            'status_merokok' => $this->faker->boolean(),
            'nama_ibu_kandung' => $this->faker->name('female'),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'jenis_kelamin' => $this->faker->numberBetween(1, 2),
            'agama' => $this->faker->numberBetween(1, 8),
            'suku' => 'Jawa',
            'bahasa_dikuasai' => 'Indonesia',
            'no_hp' => $this->faker->numerify('08##########'),
            'pendidikan' => $this->faker->numberBetween(1, 5),
            'pekerjaan' => $this->faker->numberBetween(1, 5),
            'status_pernikahan' => $this->faker->numberBetween(1, 4),
            'emergency_nama' => $this->faker->name(),
            'emergency_hubungan' => 'Keluarga',
            'emergency_no_ktp' => $this->faker->numerify('################'),
            'emergency_no_hp' => $this->faker->numerify('08##########'),
            'emergency_alamat' => $this->faker->address(),
            'alamat_ktp' => $this->faker->streetAddress(),
            'rt_ktp' => '001',
            'rw_ktp' => '002',
            'kelurahan_ktp' => 'Kelurahan A',
            'kecamatan_ktp' => 'Kecamatan B',
            'kabupaten_ktp' => 'Kota C',
            'provinsi_ktp' => 'Provinsi D',
            'kode_pos_ktp' => $this->faker->postcode(),
            'negara_ktp' => 'Indonesia',
            'alamat_domisili' => $this->faker->streetAddress(),
            'rt_domisili' => '001',
            'rw_domisili' => '002',
            'kelurahan_domisili' => 'Kelurahan A',
            'kecamatan_domisili' => 'Kecamatan B',
            'kabupaten_domisili' => 'Kota C',
            'provinsi_domisili' => 'Provinsi D',
            'kode_pos_domisili' => $this->faker->postcode(),
            'negara_domisili' => 'Indonesia',
            'penjamin' => 'Umum',
        ];
    }
}
