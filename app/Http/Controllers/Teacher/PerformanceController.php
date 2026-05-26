<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Support\InertiaPagination;
use Inertia\Inertia;
use Inertia\Response;

class PerformanceController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Teacher/Performance', [
            'students' => InertiaPagination::format(
                Student::with(['user', 'coachingClass', 'section'])
                    ->withAvg('marks', 'marks_obtained')
                    ->withCount('marks')
                    ->orderBy('admission_no')
                    ->paginate(10)
                    ->withQueryString()
            ),
        ]);
    }
}
