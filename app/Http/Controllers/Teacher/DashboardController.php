<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\Notice;
use App\Models\Routine;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $teacher = $request->user()->teacher;

        return Inertia::render('Teacher/Dashboard', [
            'stats' => [
                'assignedClasses' => $teacher ? Routine::where('teacher_id', $teacher->id)->distinct('class_id')->count('class_id') : 0,
                'marksSubmitted' => $teacher ? Mark::where('teacher_id', $teacher->id)->count() : 0,
                'notices' => Notice::whereIn('audience', ['all', 'teacher'])->count(),
            ],
        ]);
    }
}
