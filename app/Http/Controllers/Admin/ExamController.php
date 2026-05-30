<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachingClass;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\Subject;
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
        return Inertia::render('Admin/Exams', [
            'exams' => InertiaPagination::format(
                Exam::with('coachingClass')->latest()->paginate(10)->withQueryString()
            ),
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function students(Exam $exam): Response
    {
        $exam->load('coachingClass');

        $subjects = Subject::whereHas('marks', fn($m) => $m->where('exam_id', $exam->id))
            ->get(['id', 'name']);

        $students = Mark::with('student.user')
            ->where('exam_id', $exam->id)
            ->get()
            ->groupBy('student_id')
            ->map(function ($marks) {
                $student = $marks->first()->student;
                $subjectStatus = $marks->mapWithKeys(fn($m) => [$m->subject_id => $m->status]);
                return [
                    'student_id' => $student->id,
                    'name' => $student->user->name,
                    'admission_no' => $student->admission_no,
                    'subjects' => $subjectStatus,
                ];
            })
            ->values();

        return Inertia::render('Admin/Exams/Students', [
            'exam' => $exam,
            'subjects' => $subjects,
            'students' => $students,
        ]);
    }
    public function toggleStatus(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'status' => ['required', 'in:present,absent'],
        ]);

        Mark::where('exam_id', $exam->id)
            ->where('student_id', $validated['student_id'])
            ->where('subject_id', $validated['subject_id'])
            ->update(['status' => $validated['status']]);

        return back()->with('success', 'Status updated.');
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Exams/Create', [
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function edit(Exam $exam): Response
    {
        return Inertia::render('Admin/Exams/Edit', [
            'exam' => $exam,
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Exam::create($this->validated($request));

        return back()->with('success', 'Exam created.');
    }

    public function update(Request $request, Exam $exam): RedirectResponse
    {
        $exam->update($this->validated($request));

        return redirect()->route('admin.exams.index')->with('success', 'Exam updated.');
    }

    public function destroy(Exam $exam): RedirectResponse
    {
        $exam->delete();

        return back()->with('success', 'Exam deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'class_id' => ['nullable', 'exists:classes,id'],
            'name' => ['required', 'string', 'max:255'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'status' => ['required', Rule::in(['draft', 'published', 'completed'])],
        ]);
    }
}
