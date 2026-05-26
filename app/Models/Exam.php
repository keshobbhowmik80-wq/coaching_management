<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = ['class_id', 'name', 'starts_on', 'ends_on', 'status'];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
        ];
    }

    public function coachingClass(): BelongsTo
    {
        return $this->belongsTo(CoachingClass::class, 'class_id');
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }
}
