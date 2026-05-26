<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $fillable = ['class_id', 'name', 'capacity'];

    public function coachingClass(): BelongsTo
    {
        return $this->belongsTo(CoachingClass::class, 'class_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
