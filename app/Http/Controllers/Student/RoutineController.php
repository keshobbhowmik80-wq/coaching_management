<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Routine;
use App\Support\InertiaPagination;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoutineController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $student = $request->user()->student;

        return Inertia::render('Student/Routine', [
            'routines' => $student
                ? InertiaPagination::format(Routine::with(['subject', 'teacher.user', 'section'])
                    ->where('class_id', $student->class_id)
                    ->where(fn ($query) => $query->whereNull('section_id')->orWhere('section_id', $student->section_id))
                    ->orderBy('day_of_week')
                    ->orderBy('starts_at')
                    ->paginate(10)
                    ->withQueryString())
                : ['data' => [], 'links' => [], 'meta' => []],
        ]);
    }
}
