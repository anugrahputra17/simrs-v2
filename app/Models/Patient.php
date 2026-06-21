<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'no_rm',
        'gelar_kehormatan',
        'nama_lengkap',
        'nik',
        'no_bpjs',
        'no_identitas_lain',
        'status_merokok',
        'nama_ibu_kandung',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'agama_lainnya',
        'suku',
        'bahasa_dikuasai',
        'no_telepon_rumah',
        'no_hp',
        'pendidikan',
        'pekerjaan',
        'pekerjaan_lainnya',
        'status_pernikahan',
        'emergency_nama',
        'emergency_hubungan',
        'emergency_no_ktp',
        'emergency_no_hp',
        'emergency_alamat',
        'alamat_ktp',
        'rt_ktp',
        'rw_ktp',
        'kelurahan_id_ktp',
        'kecamatan_id_ktp',
        'kabupaten_id_ktp',
        'provinsi_id_ktp',
        'kode_pos_ktp',
        'negara_ktp',
        'alamat_domisili',
        'rt_domisili',
        'rw_domisili',
        'kelurahan_id_domisili',
        'kecamatan_id_domisili',
        'kabupaten_id_domisili',
        'provinsi_id_domisili',
        'kode_pos_domisili',
        'negara_domisili',
        'penjamin',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'status_merokok' => 'boolean',
        ];
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function hybridTracker()
    {
        return $this->hasOne(HybridTracker::class);
    }
}
