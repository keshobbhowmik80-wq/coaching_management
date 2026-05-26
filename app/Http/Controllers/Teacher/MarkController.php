<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use App\Support\InertiaPagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class MarkController extends Controller
{
    public function index(Request $request): Response
    {
        $teacher = $request->user()->teacher;

        return Inertia::render('Teacher/Marks', [
            'marks' => InertiaPagination::format(
                Mark::with(['exam', 'subject', 'student.user'])
                    ->when($teacher, fn ($query) => $query->where('teacher_id', $teacher->id))
                    ->latest()
                    ->paginate(10)
                    ->withQueryString()
            ),
            'exams' => Exam::orderByDesc('id')->get(['id', 'name']),
            'subjects' => Subject::orderBy('name')->get(['id', 'name', 'full_marks']),
            'students' => Student::with('user:id,name')->orderBy('admission_no')->get(['id', 'user_id', 'admission_no']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $teacher = $request->user()->teacher;
        Mark::create($this->validated($request) + ['teacher_id' => $teacher?->id]);

        return back()->with('success', 'Mark saved.');
    }

    public function update(Request $request, Mark $mark): RedirectResponse
    {
        abort_unless($mark->teacher_id === $request->user()->teacher?->id, 403);

        $mark->update($this->validated($request));

        return back()->with('success', 'Mark updated.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'student_id' => [
                'required',
                'exists:students,id',
                Rule::unique('marks')->where('exam_id', $request->integer('exam_id'))->where('subject_id', $request->integer('subject_id'))->ignore($request->route('mark')),
            ],
            'marks_obtained' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ]);
    }
}
