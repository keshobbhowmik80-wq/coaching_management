<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Support\InertiaPagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class MarkController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Marks', [
            'marks' => InertiaPagination::format(
                Mark::with(['exam', 'subject', 'student.user', 'teacher.user'])->latest()->paginate(10)->withQueryString()
            ),
            'exams' => Exam::orderByDesc('id')->get(['id', 'name']),
            'subjects' => Subject::orderBy('name')->get(['id', 'name', 'full_marks']),
            'students' => Student::with('user:id,name')->orderBy('admission_no')->get(['id', 'user_id', 'admission_no']),
            'teachers' => Teacher::with('user:id,name')->orderBy('employee_id')->get(['id', 'user_id', 'employee_id']),
        ]);
    }

    public function entry(Request $request): Response
    {
        $examId = $request->input('exam_id');
        $subjectId = $request->input('subject_id');

        $exams = Exam::orderByDesc('id')->get(['id', 'name', 'class_id']);
        $subjects = collect();
        $students = collect();

        if ($examId) {
            $subjects = Subject::whereHas('marks', fn($q) => $q->where('exam_id', $examId))
                ->get(['id', 'name', 'full_marks', 'pass_marks']);
        }

        if ($examId && $subjectId) {
            $students = Mark::with('student.user')
                ->where('exam_id', $examId)
                ->where('subject_id', $subjectId)
                ->where('status', 'present')
                ->get()
                ->map(fn($mark) => [
                    'mark_id' => $mark->id,
                    'student_id' => $mark->student_id,
                    'name' => $mark->student->user->name,
                    'admission_no' => $mark->student->admission_no,
                    'marks_obtained' => $mark->marks_obtained,
                    'status' => $mark->status,
                ]);
        }

        return Inertia::render('Admin/Marks/Entry', [
            'exams' => $exams,
            'subjects' => $subjects,
            'students' => $students,
            'filters' => [
                'exam_id' => $examId ? (int) $examId : null,
                'subject_id' => $subjectId ? (int) $subjectId : null,
            ],
        ]);
    }

    public function bulkSave(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'marks' => ['required', 'array'],
            'marks.*.mark_id' => ['required', 'exists:marks,id'],
            'marks.*.marks_obtained' => ['nullable', 'numeric', 'min:0'],
        ]);

        foreach ($validated['marks'] as $entry) {
            Mark::where('id', $entry['mark_id'])
                ->update(['marks_obtained' => $entry['marks_obtained']]);
        }

        return back()->with('success', 'Marks saved.');
    }

    public function store(Request $request): RedirectResponse
    {
        Mark::create($this->validated($request));

        return back()->with('success', 'Mark saved.');
    }

    public function update(Request $request, Mark $mark): RedirectResponse
    {
        $mark->update($this->validated($request, $mark));

        return back()->with('success', 'Mark updated.');
    }

    public function destroy(Mark $mark): RedirectResponse
    {
        $mark->delete();

        return back()->with('success', 'Mark deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Mark $mark = null): array
    {
        return $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'student_id' => [
                'required',
                'exists:students,id',
                Rule::unique('marks')->where('exam_id', $request->integer('exam_id'))->where('subject_id', $request->integer('subject_id'))->ignore($mark),
            ],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'marks_obtained' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ]);
    }
}
