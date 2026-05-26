<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\CoachingClass;
use App\Models\Exam;
use App\Support\InertiaPagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ExamController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Teacher/Exams', [
            'exams' => InertiaPagination::format(
                Exam::with('coachingClass')->latest()->paginate(10)->withQueryString()
            ),
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Exam::create($request->validate([
            'class_id' => ['nullable', 'exists:classes,id'],
            'name' => ['required', 'string', 'max:255'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'status' => ['required', Rule::in(['draft', 'published', 'completed'])],
        ]));

        return back()->with('success', 'Exam created.');
    }

    public function update(Request $request, Exam $exam): RedirectResponse
    {
        $exam->update($request->validate([
            'class_id' => ['nullable', 'exists:classes,id'],
            'name' => ['required', 'string', 'max:255'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'status' => ['required', Rule::in(['draft', 'published', 'completed'])],
        ]));

        return back()->with('success', 'Exam updated.');
    }
}
