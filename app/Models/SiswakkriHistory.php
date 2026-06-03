<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiswakkriHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswakkri_id',
        'name',
        'social_platform',
        'social_account',
        'age',
        'replaced_previous',
        'submitted_from_ip',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'replaced_previous' => 'boolean',
            'submitted_at' => 'datetime',
        ];
    }

    public function siswakkri(): BelongsTo
    {
        return $this->belongsTo(Siswakkri::class);
    }
}
