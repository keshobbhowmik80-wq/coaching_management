<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachingClass;
use App\Models\Routine;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Support\InertiaPagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoutineController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Routines', [
            'routines' => InertiaPagination::format(
                Routine::with(['coachingClass', 'section', 'subject', 'teacher.user'])->latest()->paginate(10)->withQueryString()
            ),
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
            'sections' => Section::orderBy('name')->get(['id', 'class_id', 'name']),
            'subjects' => Subject::orderBy('name')->get(['id', 'name']),
            'teachers' => Teacher::with('user:id,name')->orderBy('employee_id')->get(['id', 'user_id', 'employee_id']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Routine::create($this->validated($request));

        return back()->with('success', 'Routine created.');
    }

    public function update(Request $request, Routine $routine): RedirectResponse
    {
        $routine->update($this->validated($request));

        return back()->with('success', 'Routine updated.');
    }

    public function destroy(Routine $routine): RedirectResponse
    {
        $routine->delete();

        return back()->with('success', 'Routine deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'day_of_week' => ['required', 'string', 'max:20'],
            'starts_at' => ['required', 'date_format:H:i'],
            'ends_at' => ['required', 'date_format:H:i', 'after:starts_at'],
            'room' => ['nullable', 'string', 'max:100'],
        ]);
    }
}
