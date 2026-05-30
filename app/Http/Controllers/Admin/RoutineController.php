<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachingClass;
use App\Models\Exam;
use App\Models\Routine;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Support\InertiaPagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class RoutineController extends Controller
{
    public function index(Request $request): Response
    {
        $routines = Routine::with(['coachingClass', 'section', 'exam'])
            ->when($request->filled('type'), fn($q) => $q->where('type', $request->type))
            ->when($request->filled('class_id'), fn($q) => $q->where('class_id', $request->class_id))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Admin/Routines', [
            'routines' => InertiaPagination::format($routines),
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
            'filters' => (object) $request->only(['type', 'class_id']),
        ]);
    }

    public function show(Routine $routine): Response
    {
        $routine->load(['coachingClass', 'section', 'exam', 'slots.subject', 'slots.teacher.user']);

        return Inertia::render('Admin/Routines/Show', [
            'routine' => $routine,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Routines/Create', [
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
            'sections' => Section::orderBy('name')->get(['id', 'class_id', 'name']),
            'subjects' => Subject::orderBy('name')->get(['id', 'class_id', 'name']),
            'teachers' => Teacher::with('user:id,name')->orderBy('employee_id')->get(['id', 'user_id', 'employee_id']),
            'exams' => Exam::with('coachingClass:id,name')->orderBy('name')->get(['id', 'name', 'class_id']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:class,exam'],
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'exam_id' => ['nullable', 'exists:exams,id'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date'],
            'slots' => ['required', 'array', 'min:1'],
            'slots.*.day_of_week' => ['required', 'string', 'max:20'],
            'slots.*.subject_id' => ['nullable', 'exists:subjects,id'],
            'slots.*.teacher_id' => ['nullable', 'exists:teachers,id'],
            'slots.*.starts_at' => ['required', 'date_format:H:i'],
            'slots.*.ends_at' => ['required', 'date_format:H:i'],
            'slots.*.room' => ['nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($validated) {
            $routine = Routine::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'class_id' => $validated['class_id'],
                'section_id' => $validated['type'] === 'class' ? $validated['section_id'] : null,
                'exam_id' => $validated['type'] === 'exam' ? $validated['exam_id'] : null,
                'starts_on' => $validated['type'] === 'exam' ? $validated['starts_on'] : null,
                'ends_on' => $validated['type'] === 'exam' ? $validated['ends_on'] : null,
            ]);

            foreach ($validated['slots'] as $slot) {
                $routine->slots()->create($slot);
            }
        });

        return back()->with('success', 'Routine created.');
    }

    public function edit(Routine $routine): Response
    {
        $routine->load(['coachingClass', 'section', 'exam', 'slots.subject', 'slots.teacher.user']);

        return Inertia::render('Admin/Routines/Edit', [
            'routine' => $routine,
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
            'sections' => Section::orderBy('name')->get(['id', 'class_id', 'name']),
            'subjects' => Subject::orderBy('name')->get(['id', 'class_id', 'name']),
            'teachers' => Teacher::with('user:id,name')->orderBy('employee_id')->get(['id', 'user_id', 'employee_id']),
            'exams' => Exam::with('coachingClass:id,name')->orderBy('name')->get(['id', 'name', 'class_id']),
        ]);
    }

    public function update(Request $request, Routine $routine): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:class,exam'],
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'exam_id' => ['nullable', 'exists:exams,id'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date'],
            'slots' => ['required', 'array', 'min:1'],
            'slots.*.day_of_week' => ['required', 'string', 'max:20'],
            'slots.*.date' => ['nullable', 'date'],
            'slots.*.subject_id' => ['nullable', 'exists:subjects,id'],
            'slots.*.teacher_id' => ['nullable', 'exists:teachers,id'],
            'slots.*.starts_at' => ['required', 'date_format:H:i'],
            'slots.*.ends_at' => ['required', 'date_format:H:i'],
            'slots.*.room' => ['nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($routine, $validated) {
            $routine->update([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'class_id' => $validated['class_id'],
                'section_id' => $validated['type'] === 'class' ? $validated['section_id'] : null,
                'exam_id' => $validated['type'] === 'exam' ? $validated['exam_id'] : null,
                'starts_on' => $validated['type'] === 'exam' ? $validated['starts_on'] : null,
                'ends_on' => $validated['type'] === 'exam' ? $validated['ends_on'] : null,
            ]);

            // Remove old slots and recreate
            $routine->slots()->delete();
            foreach ($validated['slots'] as $slot) {
                $routine->slots()->create($slot);
            }
        });

        return redirect()->route('admin.routines.index')->with('success', 'Routine updated.');
    }

    public function destroy(Routine $routine): RedirectResponse
    {
        $routine->delete();

        return back()->with('success', 'Routine deleted.');
    }
}
