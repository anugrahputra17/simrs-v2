<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coding extends Model
{
    protected $fillable = [
        'medical_record_id',
        'snomed_concept_id',
        'snomed_term',
        'icd10_mapped_code',
        'is_primary_diagnosis',
        'miscoding_status',
    ];

    protected function casts(): array
    {
        return [
            'is_primary_diagnosis' => 'boolean',
        ];
    }

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
