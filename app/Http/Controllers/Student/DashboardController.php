<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $student = $request->user()->student?->load(['coachingClass', 'section', 'payments', 'marks']);

        return Inertia::render('Student/Dashboard', [
            'student' => $student,
            'stats' => [
                'average' => $student && $student->marks->isNotEmpty() ? round($student->marks->avg('marks_obtained'), 2) : 0,
                'due' => $student ? $student->payments->sum(fn ($payment) => $payment->amount_due - $payment->amount_paid) : 0,
                'payments' => $student ? $student->payments->sum('amount_paid') : 0,
                'notices' => Notice::whereIn('audience', ['all', 'student'])->count(),
            ],
        ]);
    }
}
