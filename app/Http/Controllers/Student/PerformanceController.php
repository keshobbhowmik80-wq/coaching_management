<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PerformanceController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $marks = $request->user()->student?->marks()->with(['exam', 'subject'])->get() ?? collect();

        return Inertia::render('Student/Performance', [
            'summary' => [
                'total' => $marks->sum('marks_obtained'),
                'average' => $marks->isNotEmpty() ? round($marks->avg('marks_obtained'), 2) : 0,
                'subjects' => $marks->groupBy('subject.name')->map(fn ($items, $subject) => [
                    'subject' => $subject,
                    'average' => round($items->avg('marks_obtained'), 2),
                ])->values(),
            ],
        ]);
    }
}
