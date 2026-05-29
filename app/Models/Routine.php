<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Routine extends Model
{
    protected $fillable = [
        'name',
        'class_id',
        'section_id',
        'type',
        'exam_id',
        'starts_on',
        'ends_on',
    ];

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

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function slots(): HasMany
    {
        return $this->hasMany(RoutineSlot::class)->orderBy('day_of_week')->orderBy('starts_at');
    }
}
