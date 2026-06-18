<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $fillable = [
        'registration_id',
        'subjektif',
        'objektif',
        'asesmen',
        'plan',
        'tensi',
        'nadi',
        'suhu',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function codings()
    {
        return $this->hasMany(Coding::class);
    }
}
