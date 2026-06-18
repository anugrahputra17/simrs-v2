<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HybridTracker extends Model
{
    protected $fillable = [
        'patient_id',
        'nomor_rak',
        'status_scan',
        'is_lengkap',
    ];

    protected function casts(): array
    {
        return [
            'status_scan' => 'boolean',
            'is_lengkap' => 'boolean',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
