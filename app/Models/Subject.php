<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = ['class_id', 'name', 'code', 'full_marks', 'pass_marks'];

    public function coachingClass(): BelongsTo
    {
        return $this->belongsTo(CoachingClass::class, 'class_id');
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }
}

