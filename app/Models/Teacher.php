<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = ['user_id', 'employee_id', 'phone', 'qualification', 'joined_at', 'address'];

    protected function casts(): array
    {
        return [
            'joined_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

    public function routines(): HasMany
    {
        return $this->hasMany(Routine::class);
    }
}
