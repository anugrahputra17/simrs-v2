<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'table_name',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $action, string $tableName): void
    {
        if (auth()->check()) {
            static::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'table_name' => $tableName,
            ]);
        }
    }
}
