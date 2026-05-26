<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Routine;
use App\Support\InertiaPagination;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClassController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Teacher/Classes', [
            'routines' => InertiaPagination::format(
                Routine::with(['coachingClass', 'section', 'subject'])
                    ->where('teacher_id', $request->user()->teacher?->id)
                    ->orderBy('day_of_week')
                    ->orderBy('starts_at')
                    ->paginate(10)
                    ->withQueryString()
            ),
        ]);
    }
}
