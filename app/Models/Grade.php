<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['min_percent', 'max_percent', 'grade', 'gpa'];

    public static function forPercent(float $percent): ?self
    {
        return static::where('min_percent', '<=', $percent)
            ->where('max_percent', '>=', $percent)
            ->first();
    }
}
