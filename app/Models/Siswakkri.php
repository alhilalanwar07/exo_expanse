<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswakkri extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'social_platform',
        'social_account',
        'age',
        'last_submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'last_submitted_at' => 'datetime',
        ];
    }

    public function histories(): HasMany
    {
        return $this->hasMany(SiswakkriHistory::class);
    }
}
