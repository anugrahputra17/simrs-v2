<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'nomor_rm',
        'nama',
        'nik',
        'tanggal_lahir',
        'jenis_kelamin',
        'penjamin',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
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
