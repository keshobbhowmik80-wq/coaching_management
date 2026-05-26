<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'class_id',
        'section_id',
        'admission_no',
        'guardian_name',
        'guardian_phone',
        'date_of_birth',
        'address',
        'admitted_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'admitted_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coachingClass(): BelongsTo
    {
        return $this->belongsTo(CoachingClass::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
