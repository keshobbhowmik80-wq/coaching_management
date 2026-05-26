<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoachingClass extends Model
{
    protected $table = 'classes';

    protected $fillable = ['name', 'code', 'description'];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'class_id');
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'class_id');
    }

    public function routines(): HasMany
    {
        return $this->hasMany(Routine::class, 'class_id');
    }
}
